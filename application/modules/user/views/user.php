<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link href="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    :root{
        --primary:#2563eb;--primary-dark:#1d4ed8;--primary-light:#eff6ff;
        --accent:#f59e0b;--success:#10b981;--danger:#ef4444;
        --body-bg:#f1f5f9;--card-bg:#fff;--border:#e2e8f0;
        --text:#0f172a;--muted:#64748b;
        --radius:12px;
        --shadow:0 1px 3px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.05);
        --shadow-lg:0 8px 32px rgba(0,0,0,.12);
    }
    .stat-card{background:var(--card-bg);border-radius:var(--radius);padding:18px 20px;border:1px solid var(--border);box-shadow:var(--shadow);transition:transform .2s,box-shadow .2s;}
    .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-lg);}
    .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:12px;}
    .stat-value{font-size:1.9rem;font-weight:800;line-height:1;margin-bottom:4px;}
    .stat-label{font-size:.75rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;}
    .table-card{background:var(--card-bg);border-radius:var(--radius);border:1px solid var(--border);box-shadow:var(--shadow);overflow:hidden;}
    .table-card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
    .table-card-header h6{font-weight:700;font-size:.92rem;margin:0;}
    table.dataTable thead th{background:#f8fafc!important;font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);border-color:var(--border)!important;padding:12px 14px!important;white-space:nowrap;}
    table.dataTable tbody td{padding:12px 14px!important;vertical-align:middle;border-color:var(--border)!important;font-size:.855rem;}
    table.dataTable tbody tr:hover{background:#f8fafc!important;}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{background:var(--primary)!important;color:#fff!important;border-color:var(--primary)!important;border-radius:6px!important;}
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{background:#eff6ff!important;color:var(--primary)!important;border-color:var(--border)!important;border-radius:6px!important;}
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.82rem;}
    .dataTables_wrapper .dataTables_info{font-size:.8rem;color:var(--muted);}
    .badge-driver{background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-transport{background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-pending{background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-active{background:#dcfce7;color:#166534;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-inactive{background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-approved{background:#cffafe;color:#164e63;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .badge-rejected{background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:20px;font-size:.69rem;font-weight:700;}
    .btn-action{width:31px;height:31px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;border:1.5px solid var(--border);background:#fff;color:var(--muted);font-size:.82rem;transition:all .18s;cursor:pointer;}
    .btn-action:hover{transform:scale(1.1);}
    .btn-action.edit:hover{border-color:var(--primary);color:var(--primary);background:var(--primary-light);}
    .btn-action.pwd:hover{border-color:var(--accent);color:var(--accent);background:#fffbeb;}
    .btn-action.del:hover{border-color:var(--danger);color:var(--danger);background:#fef2f2;}
    .modal-content{border:none;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.18);}
    .modal-header{background:#1e88e5;color:#fff;border-radius:16px 16px 0 0;padding:16px 22px;}
    .modal-header .modal-title{font-weight:700;font-size:.98rem;}
    .modal-header .btn-close,.modal-header .close{filter:invert(1);color:#fff;opacity:1;}
    .modal-body{padding:22px;}
    .modal-footer{padding:14px 22px;border-top:1px solid var(--border);}
    .form-label,.col-form-label{font-size:.77rem;font-weight:700;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px;display:block;}
    .form-control,.custom-select{border:1.5px solid var(--border);border-radius:8px;font-size:.87rem;padding:8px 12px;transition:border-color .2s,box-shadow .2s;}
    .form-control:focus,.custom-select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.11);outline:none;}
    .section-divider{display:flex;align-items:center;gap:10px;margin:18px 0 14px;}
    .section-divider span{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--primary);white-space:nowrap;}
    .section-divider hr{flex:1;border-color:#e2e8f0;margin:0;}
    .doc-upload-box{border:1.5px dashed #cbd5e1;border-radius:10px;padding:14px 10px;text-align:center;background:#f8fafc;transition:border-color .2s,box-shadow .2s;cursor:pointer;}
    .doc-upload-box:hover{border-color:var(--primary);background:var(--primary-light);}
    .doc-upload-box input[type=file]{display:none;}
    .doc-upload-box i{font-size:1.5rem;color:#94a3b8;display:block;margin-bottom:5px;}
    .doc-label{font-size:.76rem;font-weight:700;color:#374151;}
    .doc-upload-box small{font-size:.68rem;color:var(--muted);}
    .doc-preview{max-height:55px;border-radius:6px;object-fit:cover;margin-top:5px;cursor:zoom-in;}
    .doc-upload-box.doc-required{border-color:var(--danger)!important;border-style:solid!important;background:#fff5f5!important;box-shadow:0 0 0 3px rgba(239,68,68,.13);}
    .doc-upload-box.doc-required i{color:var(--danger)!important;}
    .doc-upload-box.doc-required .doc-label{color:var(--danger);}
    .doc-req-msg{font-size:.68rem;color:var(--danger);font-weight:600;margin-top:3px;display:none;}
    .doc-req-msg.show{display:block;}
    .password-wrapper{position:relative;}
    .password-wrapper .toggle-pwd{position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;color:var(--muted);cursor:pointer;font-size:.88rem;}
    .avatar-placeholder{border-radius:50%;background:linear-gradient(135deg,var(--primary),#6366f1);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;}
    #modalTitle{color:#ffffff;}
    .selfie-popup{position:fixed;z-index:99999;pointer-events:none;background:#fff;border-radius:12px;padding:8px;box-shadow:0 8px 32px rgba(0,0,0,.28);border:2px solid var(--primary);display:none;max-width:220px;}
    .selfie-popup img{width:130px;height:130px;object-fit:cover;border-radius:8px;display:block;margin:0 auto;}
    .selfie-popup .sp-label{font-size:.65rem;text-align:center;color:var(--muted);margin-top:4px;font-weight:600;}
    .selfie-popup .sp-address{font-size:.63rem;color:#374151;margin-top:5px;line-height:1.4;border-top:1px solid #e2e8f0;padding-top:5px;word-break:break-word;}
    #imgZoomModal{z-index:99990!important;}
    #imgZoomModal .modal-backdrop{z-index:99989!important;}
    #imgZoomModal .modal-content{background:rgba(15,23,42,.96);border-radius:16px;}
    #imgZoomModal .modal-header{background:transparent;border-bottom:none;}
    #imgZoomModal .modal-body{padding:10px;text-align:center;}
    #imgZoomModal img{max-width:100%;max-height:75vh;border-radius:10px;object-fit:contain;transition:transform .25s ease;cursor:zoom-in;}
    #imgZoomModal img.zoomed{transform:scale(1.7);cursor:zoom-out;}
    .zoom-controls{display:flex;justify-content:center;gap:10px;margin-top:10px;}
    .zoom-controls button{background:rgba(255,255,255,.12);border:none;color:#fff;border-radius:8px;padding:6px 14px;font-size:.82rem;cursor:pointer;transition:background .2s;}
    .zoom-controls button:hover{background:rgba(255,255,255,.25);}
    .zoom-label{color:rgba(255,255,255,.6);font-size:.72rem;text-align:center;margin-top:6px;}
    .form-alert{border-radius:10px;padding:10px 16px;font-size:.83rem;font-weight:600;margin-bottom:12px;display:none;}
    .form-alert.alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
    .form-alert.alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
    .field-error{border-color:var(--danger)!important;box-shadow:0 0 0 3px rgba(239,68,68,.15)!important;background:#fff5f5!important;}
    .field-error-msg{font-size:.71rem;color:var(--danger);font-weight:600;margin-top:3px;display:block;}
    .doc-upload-box.field-error{border-color:var(--danger)!important;border-style:solid!important;background:#fff5f5!important;box-shadow:0 0 0 3px rgba(239,68,68,.15)!important;}
    .doc-upload-box.field-error i{color:var(--danger)!important;}
    .input-group .field-error{z-index:1;}
    .pan-hint{font-size:.68rem;color:var(--muted);margin-top:3px;}
    .loc-loading{font-size:.72rem;color:var(--primary);display:none;}
    .loc-loading.show{display:inline;}
    .field-checking{border-color:#94a3b8!important;background:#f8fafc!important;}
    .field-exists{border-color:var(--danger)!important;box-shadow:0 0 0 3px rgba(239,68,68,.13)!important;background:#fff5f5!important;}
    .field-ok{border-color:var(--success)!important;box-shadow:0 0 0 3px rgba(16,185,129,.10)!important;}
    .field-msg{font-size:.72rem;font-weight:600;margin-top:4px;display:none;}
    .field-msg.show-err{display:block;color:var(--danger);}
    .field-msg.show-ok{display:block;color:var(--success);}
    .aadhar-lock-wrap{position:relative;}
    .aadhar-locked{background:#f1f5f9!important;color:#94a3b8!important;cursor:not-allowed!important;}
    .aadhar-unlock-row{display:flex;align-items:center;gap:6px;margin-top:5px;}
    .aadhar-unlock-row label{font-size:.71rem;font-weight:600;color:#64748b;margin:0;cursor:pointer;text-transform:none;letter-spacing:0;}
    .aadhar-unlock-row input[type=checkbox]{width:14px;height:14px;cursor:pointer;accent-color:var(--danger);}
    .unlock-warn{font-size:.68rem;color:#b45309;background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:3px 8px;display:none;}
    .unlock-warn.show{display:inline-block;}
    .btn-primary{color:#fff;background-color:#1e88e5;border-color:#1e88e5;}
    .btn-primary:hover{color:#fff;background-color:#1a7bd0;border-color:#1a7bd0;}
    .email-info-box{background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:7px 12px;font-size:.72rem;color:#0369a1;display:flex;align-items:center;gap:6px;margin-top:5px;}
    /* ── Filter dropdowns loading state ── */
    .filter-loading{opacity:.6;pointer-events:none;}
    @keyframes spin{to{transform:rotate(360deg);}}
    .spin{display:inline-block;animation:spin .7s linear infinite;}
</style>

<?php
$csrf_name = isset($csrf_name) ? $csrf_name : $this->security->get_csrf_token_name();
$csrf_hash = isset($csrf_hash) ? $csrf_hash : $this->security->get_csrf_hash();
$countries = isset($countries) ? $countries : array();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body">

                <!-- STAT CARDS -->
                <div class="row g-3 mb-4">
                    <?php
                    $cards = array(
                        array('key'=>'total',     'label'=>'Total',     'icon'=>'people-fill',        'bg'=>'#eff6ff','color'=>'#2563eb'),
                        array('key'=>'pending',   'label'=>'Pending',   'icon'=>'hourglass-split',    'bg'=>'#fffbeb','color'=>'#f59e0b'),
                        array('key'=>'active',    'label'=>'Active',    'icon'=>'check-circle-fill',  'bg'=>'#f0fdf4','color'=>'#10b981'),
                        array('key'=>'inactive',  'label'=>'Inactive',  'icon'=>'slash-circle',       'bg'=>'#f1f5f9','color'=>'#64748b'),
                        array('key'=>'driver',    'label'=>'Drivers',   'icon'=>'id-card-fill',       'bg'=>'#f0f9ff','color'=>'#0ea5e9'),
                        array('key'=>'transport', 'label'=>'Transport', 'icon'=>'truck-front-fill',   'bg'=>'#f5f3ff','color'=>'#8b5cf6'),
                    );
                    foreach ($cards as $c): ?>
                        <div class="col-6 col-md-2">
                            <div class="stat-card">
                                <div class="stat-icon" style="background:<?php echo $c['bg']; ?>;color:<?php echo $c['color']; ?>">
                                    <i class="bi bi-<?php echo $c['icon']; ?>"></i>
                                </div>
                                <div class="stat-value"><?php echo isset($stats[$c['key']]) ? (int)$stats[$c['key']] : 0; ?></div>
                                <div class="stat-label"><?php echo $c['label']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- TABLE CARD -->
                <div class="table-card">
                    <div class="table-card-header">
                        <i class="bi bi-table text-primary"></i>
                        <h6>All Registrations</h6>
                        <small class="text-muted ml-1" id="recordInfo"></small>
                        <div class="ml-auto d-flex flex-wrap" style="gap:8px">

                            <!-- Status Filter -->
                            <select class="form-control form-control-sm" id="filterStatus" style="width:130px;height:34px;" onchange="reloadTable()">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="rejected">Rejected</option>
                            </select>

                            <!-- Type Filter -->
                            <select class="form-control form-control-sm" id="filterType" style="width:130px;height:34px;" onchange="reloadTable()">
                                <option value="">All Types</option>
                                <option value="DRIVER">Driver</option>
                                <option value="TRANSPORT">Transport</option>
                            </select>

                            <!-- District Filter — populated via AJAX on page load -->
                            <select class="form-control form-control-sm" id="filterDistrict" style="width:150px;height:34px;" onchange="onFilterDistrictChange()">
                                <option value="">All Districts</option>
                            </select>

                            <!-- Mandal Filter — populated when district is selected -->
                            <select class="form-control form-control-sm" id="filterMandal" style="width:150px;height:34px;" onchange="reloadTable()" disabled>
                                <option value="">All Mandals</option>
                            </select>

                            <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()" title="Clear Filters">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="reloadTable()" title="Refresh">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="openModal(null)">
                                <i class="bi bi-plus-lg"></i> Add New
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="regTable" class="table table-hover mb-0 w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Mobile / Name</th>
                                <th>Reg. Key</th>
                                <th>Language</th>
                                <th>Type</th>
                                <th>Aadhar No</th>
                                <th>Status</th>
                                <th>Registered On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SELFIE POPUP -->
<div id="selfiePopup" class="selfie-popup">
    <img id="selfiePopupImg" src="" alt="Selfie">
    <div class="sp-label"><i class="bi bi-person-circle"></i> Selfie</div>
    <div class="sp-address" id="selfiePopupAddr" style="display:none"></div>
</div>

<!-- IMAGE ZOOM MODAL -->
<div class="modal fade" id="imgZoomModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:99990">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding:12px 18px">
                <span class="text-white" style="font-size:.85rem;font-weight:700" id="zoomLabel">Document Preview</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background:#ef4444;border:none;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;color:#fff;opacity:1;font-size:1.1rem;cursor:pointer;padding:0;transition:background .2s;"
                        onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:rgba(15,23,42,.92)">
                <img id="zoomImg" src="" alt="Preview" onclick="toggleZoom(this)">
                <div class="zoom-controls">
                    <button onclick="zoomIn()"><i class="bi bi-zoom-in"></i> Zoom In</button>
                    <button onclick="zoomReset()"><i class="bi bi-aspect-ratio"></i> Reset</button>
                    <button onclick="zoomOut()"><i class="bi bi-zoom-out"></i> Zoom Out</button>
                    <a id="zoomDownload" href="#" target="_blank" style="text-decoration:none">
                        <button type="button"><i class="bi bi-download"></i> Open</button>
                    </a>
                </div>
                <div class="zoom-label">Click image to toggle zoom &nbsp;|&nbsp; Use buttons for fine control</div>
            </div>
        </div>
    </div>
</div>

<!-- REGISTRATION MODAL -->
<div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:960px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-badge mr-2"></i>
                    <span id="modalTitle">Add Registration</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:75vh;overflow-y:auto">

                <div id="formAlert" class="form-alert"></div>

                <form id="regForm" novalidate>
                    <input type="hidden" id="reg_csrf_field" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_hash; ?>">
                    <input type="hidden" id="tr_id" name="tr_id" value="">

                    <!-- Basic Info -->
                    <div class="section-divider"><span>Basic Information</span><hr></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="bi bi-phone"></i></span></div>
                                    <input type="text" class="form-control" id="tr_mobile" name="tr_mobile" maxlength="10" placeholder="10-digit mobile" oninput="debounceMobileCheck(this)">
                                </div>
                                <div id="mobile_msg" class="field-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Language <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_language" name="tr_language">
                                    <option value="">-- Select --</option>
                                    <option value="ENGLISH">English</option>
                                    <option value="HINDI">Hindi</option>
                                    <option value="TELUGU">Telugu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Registration Type <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_registration_type" name="tr_registration_type">
                                    <option value="">-- Select --</option>
                                    <option value="DRIVER">Driver</option>
                                    <option value="TRANSPORT">Transport</option>
                                </select>
                            </div>
                        </div>

                        <!-- Email field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="bi bi-envelope"></i></span></div>
                                    <input type="email" class="form-control" id="tr_email" name="tr_email" placeholder="driver@email.com">
                                </div>
                                <div class="email-info-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    Login credentials will be sent to this email when status is set to <strong>Active</strong>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="section-divider mt-4"><span>Personal Details</span><hr></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Full Name (as per Aadhar)</label>
                                <input type="text" class="form-control" id="tr_full_name" name="tr_full_name" placeholder="Full name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">PAN Card No <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span></div>
                                    <input type="text" class="form-control" id="tr_pan_no" name="tr_pan_no"
                                            maxlength="10" placeholder="ABCDE1234F"
                                            style="font-family:monospace;text-transform:uppercase;letter-spacing:1px"
                                            oninput="this.value=this.value.toUpperCase();panLiveCheck(this)">
                                </div>
                                <div class="pan-hint"><i class="bi bi-info-circle"></i> Format: 5 letters &bull; 4 digits &bull; 1 letter &nbsp;(e.g. ABCDE1234F)</div>
                                <div id="pan_msg" class="field-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Aadhar No <span class="text-danger">*</span></label>
                                <div class="input-group aadhar-lock-wrap">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="bi bi-shield-lock" id="aadhar_icon"></i></span></div>
                                    <input type="text" class="form-control" id="tr_aadhar_no" name="tr_aadhar_no" maxlength="12" placeholder="12-digit Aadhar" style="font-family:monospace" oninput="debounceAadharCheck(this)">
                                </div>
                                <div class="aadhar-unlock-row d-none" id="aadhar_unlock_row">
                                    <input type="checkbox" id="aadhar_unlock_chk" onchange="toggleAadharLock(this)">
                                    <label for="aadhar_unlock_chk"><i class="bi bi-unlock text-danger"></i> Enable editing of Aadhar No</label>
                                </div>
                                <span class="unlock-warn" id="aadhar_unlock_warn">&#9888; Be careful — Aadhar changes affect KYC records</span>
                                <div id="aadhar_msg" class="field-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="tr_dob" name="tr_dob">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Full Address (as per Aadhar)</label>
                                <textarea class="form-control" id="tr_full_address" name="tr_full_address" rows="2" placeholder="Full address"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="section-divider mt-3"><span>Location <span class="loc-loading" id="locLoading"><i class="bi bi-arrow-repeat spin"></i> Loading...</span></span><hr></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_country_id" name="tr_country_id" onchange="onCountryChange(this)">
                                    <option value="">-- Select Country --</option>
                                    <?php foreach ($countries as $c): ?>
                                        <option value="<?php echo $c['tc_country_ID']; ?>"><?php echo htmlspecialchars($c['tc_country_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" id="tr_country" name="tr_country">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_state_id" name="tr_state_id" onchange="onStateChange(this)" disabled>
                                    <option value="">-- Select State --</option>
                                </select>
                                <input type="hidden" id="tr_state" name="tr_state">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_district_id" name="tr_district_id" onchange="onDistrictChange(this)" disabled>
                                    <option value="">-- Select District --</option>
                                </select>
                                <input type="hidden" id="tr_district" name="tr_district">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mandal <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="tr_mandal_id" name="tr_mandal_id" onchange="onMandalChange(this)" disabled>
                                    <option value="">-- Select Mandal --</option>
                                </select>
                                <input type="hidden" id="tr_mandal" name="tr_mandal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">City / Village <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tr_village" name="tr_village" placeholder="Enter city or village name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tr_pincode" name="tr_pincode" maxlength="6" placeholder="6-digit PIN">
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="section-divider mt-4"><span>Documents &amp; Photos</span><hr></div>
                    <div class="row">
                        <?php
                        $docs = array(
                            array('field'=>'tr_selfie',         'file_id'=>'f_selfie','icon'=>'camera-fill',        'label'=>'Selfie'),
                            array('field'=>'tr_pan_copy',       'file_id'=>'f_pan',  'icon'=>'credit-card-2-front', 'label'=>'PAN Copy'),
                            array('field'=>'tr_aadhar_front',   'file_id'=>'f_adf',  'icon'=>'card-image',          'label'=>'Aadhar Front'),
                            array('field'=>'tr_aadhar_back',    'file_id'=>'f_adb',  'icon'=>'card-image',          'label'=>'Aadhar Back'),
                            array('field'=>'tr_transport_front','file_id'=>'f_trf',  'icon'=>'truck-front',         'label'=>'Transport/DL Front'),
                            array('field'=>'tr_transport_back', 'file_id'=>'f_trb',  'icon'=>'truck-front',         'label'=>'Transport/DL Back'),
                        );
                        foreach ($docs as $d): ?>
                            <div class="col-6 col-md-2">
                                <label class="form-label text-center d-block" style="font-size:.68rem"><?php echo $d['label']; ?></label>
                                <div class="doc-upload-box" id="box_<?php echo $d['file_id']; ?>" onclick="document.getElementById('<?php echo $d['file_id']; ?>').click()">
                                    <input type="file" id="<?php echo $d['file_id']; ?>" name="<?php echo $d['field']; ?>" accept=".jpg,.jpeg,.png,.pdf"
                                            onchange="previewDoc(this,'prev_<?php echo $d['file_id']; ?>','view_<?php echo $d['file_id']; ?>')">
                                    <i class="bi bi-<?php echo $d['icon']; ?>"></i>
                                    <div class="doc-label">Upload</div>
                                    <small>JPG/PNG/PDF</small>
                                </div>
                                <div class="doc-req-msg" id="req_<?php echo $d['file_id']; ?>">Required!</div>
                                <div class="text-center mt-1">
                                    <img id="prev_<?php echo $d['file_id']; ?>" class="doc-preview d-none" src="" alt="" onclick="openZoom(this.src,'<?php echo $d['label']; ?>')" title="Click to zoom">
                                    <button type="button" id="view_<?php echo $d['file_id']; ?>" class="d-none btn btn-outline-success btn-sm mt-1" style="font-size:.68rem"
                                            data-label="<?php echo $d['label']; ?>" data-url="" onclick="openZoomFromBtn(this)">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Status -->
                    <div class="section-divider mt-4"><span>Account Status</span><hr></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control custom-select" id="tr_status" name="tr_status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="saveBtn" onclick="saveRegistration()">
                    <span class="btn-text"><i class="bi bi-save mr-1"></i> Save Registration</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div class="modal fade" id="pwdModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="bi bi-key-fill mr-2"></i>Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:#ffffff">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pwd_tr_id">
                <input type="hidden" id="pwd_csrf_field" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_hash; ?>">
                <div id="pwdAlert" class="form-alert"></div>
                <p class="text-muted mb-3" style="font-size:.82rem">Mobile: <strong id="pwd_mobile"></strong></p>
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="new_password" placeholder="Min. 6 characters">
                        <button class="toggle-pwd" type="button" onclick="togglePwd('new_password',this)"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="confirm_password" placeholder="Re-enter password">
                        <button class="toggle-pwd" type="button" onclick="togglePwd('confirm_password',this)"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="email-info-box mt-2">
                    <i class="bi bi-envelope-check-fill"></i>
                    New password will be automatically sent to the driver's registered email.
                </div>
                <div class="text-right mt-2">
                    <button class="btn btn-link btn-sm p-0 text-muted" type="button" onclick="generatePwd()">
                        <i class="bi bi-shuffle"></i> Generate Strong Password
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary text-white font-weight-bold" id="pwdSaveBtn" onclick="changePassword()">
                    <span class="btn-text"><i class="bi bi-check-lg mr-1"></i> Update &amp; Send Email</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var BASE_URL  = '<?php echo base_url("user/users/"); ?>';
    var CSRF_NAME = '<?php echo $csrf_name; ?>';
    var _zoomScale = 1;

    // ── CSRF ──
    var Csrf = {
        token: '<?php echo $csrf_hash; ?>',
        get: function(){ return this.token; },
        update: function(h){ if(!h) return; this.token=h; var f=document.querySelectorAll('#reg_csrf_field,#pwd_csrf_field'); for(var i=0;i<f.length;i++) f[i].value=h; },
        injectInto: function(fd){ try{ fd.delete(CSRF_NAME); }catch(e){} fd.append(CSRF_NAME,this.token); return fd; },
        asObj: function(){ var o={}; o[CSRF_NAME]=this.token; return o; }
    };

    // ── AJAX HELPER ──
    function ajaxPost(url, payload, onSuccess, onError){
        var fd;
        if(payload instanceof FormData){ fd=Csrf.injectInto(payload); }
        else {
            fd=new FormData();
            var merged=payload||{}; merged[CSRF_NAME]=Csrf.get();
            for(var k in merged){ if(merged.hasOwnProperty(k)) fd.append(k,merged[k]); }
        }
        $.ajax({ url:url, type:'POST', data:fd, processData:false, contentType:false,
            success:function(res){
                if(res&&res.csrf_token) Csrf.update(res.csrf_token);
                if(res&&res.status==='success'){ if(typeof onSuccess==='function') onSuccess(res); }
                else { var msg=(res&&res.message)?res.message:'Something went wrong'; if(typeof onError==='function') onError(res); else showToast(msg,'error'); }
            },
            error:function(xhr){
                var res=null; try{ res=JSON.parse(xhr.responseText); }catch(e){}
                if(res&&res.csrf_token) Csrf.update(res.csrf_token);
                var msg=(res&&res.message)?res.message:'Server error. Please try again.';
                showToast(msg,'error'); if(typeof onError==='function') onError(res||{});
            }
        });
    }

    // ── DATATABLES ──
    var dt;
    $(document).ready(function(){
        dt=$('#regTable').DataTable({
            processing:true, serverSide:true, pageLength:10,
            lengthMenu:[[10,25,50,100],[10,25,50,100]],
            ajax:{
                url:BASE_URL+'datatableajax', type:'POST',
                data:function(d){
                    d[CSRF_NAME]        = Csrf.get();
                    d.filter_status     = $('#filterStatus').val();
                    d.filter_type       = $('#filterType').val();
                    d.filter_district   = $('#filterDistrict').val();
                    d.filter_mandal     = $('#filterMandal').val();
                    return d;
                },
                dataSrc:function(json){ if(json&&json.csrf_token) Csrf.update(json.csrf_token); $('#recordInfo').text('Showing '+(json.recordsFiltered||0)+' of '+(json.recordsTotal||0)+' records'); return json.data||[]; },
                error:function(){ showToast('Failed to load table data.','error'); }
            },
            columns:[
                {data:0,width:'50px'},{data:1},{data:2},
                {data:3,width:'90px'},{data:4,width:'110px'},{data:5},
                {data:6,width:'100px'},{data:7,width:'130px'},
                {data:8,orderable:false,searchable:false,width:'140px',className:'text-center'}
            ],
            language:{
                search:'',searchPlaceholder:'Search mobile, name, aadhar...',
                processing:'<div class="text-primary"><span class="spinner-border spinner-border-sm mr-1"></span> Loading...</div>',
                emptyTable:'<div class="text-center py-3 text-muted">No registrations found</div>',
                zeroRecords:'<div class="text-center py-3 text-muted">No matching records</div>'
            },
            drawCallback:function(){ bindSelfieHover(); }
        });

        // Load all districts for filter dropdown on page load
        loadFilterDistricts();
    });

    function reloadTable(){ if(dt) dt.ajax.reload(null,false); }

    // ── CLEAR ALL FILTERS ──
    function clearFilters(){
        document.getElementById('filterStatus').value   = '';
        document.getElementById('filterType').value     = '';
        document.getElementById('filterDistrict').value = '';
        var mandalSel = document.getElementById('filterMandal');
        mandalSel.innerHTML = '<option value="">All Mandals</option>';
        mandalSel.disabled  = true;
        reloadTable();
    }

    // ── FILTER: Load all districts for the filter dropdown on page load ──
    function loadFilterDistricts(){
        var fd = new FormData();
        fd.append(CSRF_NAME, Csrf.get());
        $.ajax({
            url: BASE_URL + 'get_all_districts',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                if(res && res.csrf_token) Csrf.update(res.csrf_token);
                var sel = document.getElementById('filterDistrict');
                if(!sel || !res.data) return;
                res.data.forEach(function(d){
                    var opt = document.createElement('option');
                    opt.value       = d.tdt_district_ID;
                    opt.textContent = d.tdt_district_name;
                    sel.appendChild(opt);
                });
            }
        });
    }

    // ── FILTER: When district changes, load its mandals ──
    function onFilterDistrictChange(){
        var distId    = document.getElementById('filterDistrict').value;
        var mandalSel = document.getElementById('filterMandal');

        // Reset mandal dropdown
        mandalSel.innerHTML = '<option value="">All Mandals</option>';
        mandalSel.disabled  = true;

        // Reload table immediately with just the district filter
        reloadTable();

        if(!distId) return;

        // Load mandals for selected district
        ajaxPost(BASE_URL+'get_mandals', {district_id: distId}, function(res){
            (res.data || []).forEach(function(m){
                var opt = document.createElement('option');
                // Use mandal name as value — matches tr_mandal column (stores name, not ID)
                opt.value       = m.tm_mandal;
                opt.textContent = m.tm_mandal;
                mandalSel.appendChild(opt);
            });
            if(mandalSel.options.length > 1) mandalSel.disabled = false;
        });
    }

    // ── SELFIE HOVER ──
    function bindSelfieHover(){
        var popup=document.getElementById('selfiePopup');
        var popImg=document.getElementById('selfiePopupImg');
        var popAddr=document.getElementById('selfiePopupAddr');
        $(document).off('mouseenter.selfie mouseleave.selfie mousemove.selfie');
        $(document).on('mouseenter.selfie','.selfie-hover-trigger',function(e){
            var url=$(this).data('selfie'); var addr=$(this).data('address')||'';
            if(!url&&!addr) return;
            if(url){ popImg.src=url; popImg.style.display='block'; } else { popImg.style.display='none'; }
            if(addr&&popAddr){ popAddr.textContent=addr; popAddr.style.display='block'; } else if(popAddr){ popAddr.style.display='none'; }
            popup.style.display='block'; positionPopup(e);
        }).on('mousemove.selfie','.selfie-hover-trigger',function(e){
            positionPopup(e);
        }).on('mouseleave.selfie','.selfie-hover-trigger',function(){
            popup.style.display='none'; popImg.src='';
        });
    }
    function positionPopup(e){
        var popup=document.getElementById('selfiePopup'); var pw=popup.offsetWidth||230;
        var x=e.clientX+16; var y=e.clientY-80;
        if(x+pw+10>window.innerWidth) x=e.clientX-pw-10;
        if(y<0) y=4; popup.style.left=x+'px'; popup.style.top=y+'px';
    }

    // ── IMAGE ZOOM ──
    function openZoom(src,label){
        if(!src||src==='') return;
        _zoomScale=1;
        var img=document.getElementById('zoomImg');
        img.src=src; img.style.transform='scale(1)'; img.classList.remove('zoomed'); img.style.cursor='zoom-in';
        document.getElementById('zoomLabel').textContent=label||'Document Preview';
        document.getElementById('zoomDownload').href=src;
        $('#imgZoomModal').modal({backdrop:true,keyboard:true,show:true});
        setTimeout(function(){ $('.modal-backdrop').last().css('z-index','99989'); $('#imgZoomModal').css('z-index','99990'); },50);
    }
    function openZoomFromBtn(btn){
        var url=btn.getAttribute('data-url')||''; var label=btn.getAttribute('data-label')||'Document';
        if(!url) return;
        if(/\.pdf(\?.*)?$/i.test(url)){ window.open(url,'_blank'); } else { openZoom(url,label); }
    }
    function toggleZoom(img){
        if(img.classList.contains('zoomed')){ img.classList.remove('zoomed'); _zoomScale=1; img.style.transform='scale(1)'; img.style.cursor='zoom-in'; }
        else { img.classList.add('zoomed'); _zoomScale=1.7; img.style.transform='scale(1.7)'; img.style.cursor='zoom-out'; }
    }
    function zoomIn(){ _zoomScale=Math.min(_zoomScale+0.3,4); var img=document.getElementById('zoomImg'); img.style.transform='scale('+_zoomScale+')'; img.classList.toggle('zoomed',_zoomScale>1); }
    function zoomOut(){ _zoomScale=Math.max(_zoomScale-0.3,0.5); var img=document.getElementById('zoomImg'); img.style.transform='scale('+_zoomScale+')'; img.classList.toggle('zoomed',_zoomScale>1); }
    function zoomReset(){ _zoomScale=1; var img=document.getElementById('zoomImg'); img.style.transform='scale(1)'; img.classList.remove('zoomed'); img.style.cursor='zoom-in'; }

    // ── AADHAR LOCK ──
    function lockAadhar(locked){
        var inp=document.getElementById('tr_aadhar_no'); var icon=document.getElementById('aadhar_icon');
        var row=document.getElementById('aadhar_unlock_row'); var chk=document.getElementById('aadhar_unlock_chk');
        if(!inp) return;
        if(locked){ inp.readOnly=true; inp.classList.add('aadhar-locked'); if(icon) icon.className='bi bi-lock-fill text-danger'; if(row) row.classList.remove('d-none'); if(chk) chk.checked=false; }
        else { inp.readOnly=false; inp.classList.remove('aadhar-locked'); if(icon) icon.className='bi bi-shield-lock'; if(row) row.classList.add('d-none'); if(chk) chk.checked=false; document.getElementById('aadhar_unlock_warn').classList.remove('show'); }
    }
    function toggleAadharLock(chk){
        var inp=document.getElementById('tr_aadhar_no'); var icon=document.getElementById('aadhar_icon'); var warn=document.getElementById('aadhar_unlock_warn');
        if(chk.checked){ inp.readOnly=false; inp.classList.remove('aadhar-locked'); if(icon) icon.className='bi bi-unlock-fill text-warning'; if(warn) warn.classList.add('show'); inp.focus(); }
        else { inp.readOnly=true; inp.classList.add('aadhar-locked'); if(icon) icon.className='bi bi-lock-fill text-danger'; if(warn) warn.classList.remove('show'); clearFieldState('tr_aadhar_no'); document.getElementById('aadhar_msg').className='field-msg'; document.getElementById('aadhar_msg').textContent=''; }
    }

    // ── DUPLICATE CHECK ──
    var _mobileTimer=null,_aadharTimer=null,_mobileXhr=null,_aadharXhr=null;
    function clearFieldState(id){ var el=document.getElementById(id); if(!el) return; el.classList.remove('field-exists','field-ok','field-checking'); }
    function setFieldState(id,state){ var el=document.getElementById(id); if(!el) return; el.classList.remove('field-exists','field-ok','field-checking'); if(state) el.classList.add('field-'+state); }
    function setFieldMsg(msgId,text,type){ var el=document.getElementById(msgId); if(!el) return; el.textContent=text; el.className='field-msg'+(type?' show-'+type:''); }

    function debounceMobileCheck(inp){ clearTimeout(_mobileTimer); var val=inp.value.trim(); if(val.length<10){ clearFieldState('tr_mobile'); setFieldMsg('mobile_msg','',''); return; } setFieldState('tr_mobile','checking'); setFieldMsg('mobile_msg','Checking...',''); _mobileTimer=setTimeout(function(){ checkMobile(val); },600); }
    function checkMobile(val){
        var trId=document.getElementById('tr_id').value||0; var origVal=document.getElementById('tr_mobile')._origVal||'';
        if(val===origVal){ clearFieldState('tr_mobile'); setFieldMsg('mobile_msg','',''); return; }
        if(isNaN(val)||val.length!==10) return;
        if(_mobileXhr) _mobileXhr.abort();
        var fd=new FormData(); fd.append(CSRF_NAME,Csrf.get()); fd.append('mobile',val); fd.append('tr_id',trId);
        _mobileXhr=$.ajax({ url:BASE_URL+'check_mobile', type:'POST', data:fd, processData:false, contentType:false,
            success:function(res){ if(res&&res.csrf_token) Csrf.update(res.csrf_token); if(res&&res.exists){ setFieldState('tr_mobile','exists'); setFieldMsg('mobile_msg','&#9888; This mobile number is already registered!','err'); } else { setFieldState('tr_mobile','ok'); setFieldMsg('mobile_msg','&#10003; Mobile number is available','ok'); } }
        });
    }
    function debounceAadharCheck(inp){ if(inp.readOnly) return; clearTimeout(_aadharTimer); var val=inp.value.trim(); if(val.length<12){ clearFieldState('tr_aadhar_no'); setFieldMsg('aadhar_msg','',''); return; } setFieldState('tr_aadhar_no','checking'); setFieldMsg('aadhar_msg','Checking...',''); _aadharTimer=setTimeout(function(){ checkAadhar(val); },600); }
    function checkAadhar(val){
        var trId=document.getElementById('tr_id').value||0; var origVal=document.getElementById('tr_aadhar_no')._origVal||'';
        if(val===origVal){ clearFieldState('tr_aadhar_no'); setFieldMsg('aadhar_msg','',''); return; }
        if(val.length!==12) return;
        if(_aadharXhr) _aadharXhr.abort();
        var fd=new FormData(); fd.append(CSRF_NAME,Csrf.get()); fd.append('aadhar',val); fd.append('tr_id',trId);
        _aadharXhr=$.ajax({ url:BASE_URL+'check_aadhar', type:'POST', data:fd, processData:false, contentType:false,
            success:function(res){ if(res&&res.csrf_token) Csrf.update(res.csrf_token); if(res&&res.exists){ setFieldState('tr_aadhar_no','exists'); setFieldMsg('aadhar_msg','&#9888; This Aadhar number is already registered!','err'); } else { setFieldState('tr_aadhar_no','ok'); setFieldMsg('aadhar_msg','&#10003; Aadhar number is available','ok'); } }
        });
    }
    function hasDuplicateFields(){
        var mobileEl=document.getElementById('tr_mobile'); var aadharEl=document.getElementById('tr_aadhar_no');
        if(mobileEl&&mobileEl.classList.contains('field-exists')){ showFormAlert('Mobile number is already registered. Please use a different number.','error'); mobileEl.focus(); return true; }
        if(aadharEl&&aadharEl.classList.contains('field-exists')){ showFormAlert('Aadhar number is already registered. Please verify and try again.','error'); if(!aadharEl.readOnly) aadharEl.focus(); return true; }
        return false;
    }

    // ── LOCATION DROPDOWNS ──
    function showLocLoading(show){ var el=document.getElementById('locLoading'); if(el) el.classList.toggle('show',show); }
    function populateSelect(selId,items,idKey,nameKey,placeholder){ var sel=document.getElementById(selId); if(!sel) return; sel.innerHTML='<option value="">'+placeholder+'</option>'; for(var i=0;i<items.length;i++){ var opt=document.createElement('option'); opt.value=items[i][idKey]; opt.textContent=items[i][nameKey]; sel.appendChild(opt); } sel.disabled=(items.length===0); }
    function resetSelect(selId,placeholder){ var sel=document.getElementById(selId); if(!sel) return; sel.innerHTML='<option value="">'+placeholder+'</option>'; sel.disabled=true; }

    function onCountryChange(sel){
        var id=sel.value; var name=sel.options[sel.selectedIndex]?sel.options[sel.selectedIndex].text:'';
        document.getElementById('tr_country').value=(id?name:'');
        resetSelect('tr_state_id','-- Select State --'); resetSelect('tr_district_id','-- Select District --'); resetSelect('tr_mandal_id','-- Select Mandal --');
        document.getElementById('tr_state').value=''; document.getElementById('tr_district').value=''; document.getElementById('tr_mandal').value=''; document.getElementById('tr_village').value='';
        if(!id) return;
        showLocLoading(true);
        ajaxPost(BASE_URL+'get_states',{country_id:id},function(res){ showLocLoading(false); populateSelect('tr_state_id',res.data||[],'ts_state_ID','ts_state_name','-- Select State --'); },function(){ showLocLoading(false); });
    }
    function onStateChange(sel){
        var id=sel.value; var name=sel.options[sel.selectedIndex]?sel.options[sel.selectedIndex].text:'';
        document.getElementById('tr_state').value=(id?name:'');
        resetSelect('tr_district_id','-- Select District --'); resetSelect('tr_mandal_id','-- Select Mandal --');
        document.getElementById('tr_district').value=''; document.getElementById('tr_mandal').value=''; document.getElementById('tr_village').value='';
        if(!id) return;
        showLocLoading(true);
        ajaxPost(BASE_URL+'get_districts',{state_id:id},function(res){ showLocLoading(false); populateSelect('tr_district_id',res.data||[],'tdt_district_ID','tdt_district_name','-- Select District --'); },function(){ showLocLoading(false); });
    }
    function onDistrictChange(sel){
        var id=sel.value; var name=sel.options[sel.selectedIndex]?sel.options[sel.selectedIndex].text:'';
        document.getElementById('tr_district').value=(id?name:'');
        resetSelect('tr_mandal_id','-- Select Mandal --'); document.getElementById('tr_mandal').value='';
        if(!id) return;
        showLocLoading(true);
        ajaxPost(BASE_URL+'get_mandals',{district_id:id},function(res){ showLocLoading(false); populateSelect('tr_mandal_id',res.data||[],'tm_mandal_ID','tm_mandal','-- Select Mandal --'); },function(){ showLocLoading(false); });
    }
    function onMandalChange(sel){
        var id=sel.value; var name=sel.options[sel.selectedIndex]?sel.options[sel.selectedIndex].text:'';
        document.getElementById('tr_mandal').value=(id?name:'');
    }

    function restoreLocation(r){
        var stateId=parseInt(r.tr_state_id_val||r.tr_state||0); var districtId=parseInt(r.tr_district_id_val||r.tr_district||0);
        var countryId=parseInt(r.tr_country_id_val||0); var stateName=r.tr_state_name||''; var distName=r.tr_district_name||'';
        var mandalName=r.tr_mandal||''; var village=r.tr_village||'';
        var villEl=document.getElementById('tr_village'); if(villEl) villEl.value=village;
        if(!stateId) return;
        showLocLoading(true);
        if(countryId){
            var countrySel=document.getElementById('tr_country_id');
            if(countrySel){
                countrySel.value=countryId;
                ajaxPost(BASE_URL+'get_states',{country_id:countryId},function(resS){
                    populateSelect('tr_state_id',resS.data||[],'ts_state_ID','ts_state_name','-- Select State --');
                    var stateSel=document.getElementById('tr_state_id'); if(stateSel){ stateSel.value=stateId; stateSel.disabled=false; }
                    document.getElementById('tr_state').value=stateName;
                    if(!districtId){ showLocLoading(false); return; }
                    _restoreDistrict(stateId,districtId,distName,mandalName);
                },function(){ showLocLoading(false); });
                return;
            }
        }
        var stateSel=document.getElementById('tr_state_id');
        if(stateSel){ var opt=new Option(stateName||'State #'+stateId,stateId,true,true); stateSel.innerHTML='<option value="">-- Select State --</option>'; stateSel.appendChild(opt); stateSel.disabled=false; document.getElementById('tr_state').value=stateName; }
        if(!districtId){ showLocLoading(false); return; }
        _restoreDistrict(stateId,districtId,distName,mandalName);
    }
    function _restoreDistrict(stateId,districtId,distName,mandalName){
        ajaxPost(BASE_URL+'get_districts',{state_id:stateId},function(res2){
            populateSelect('tr_district_id',res2.data||[],'tdt_district_ID','tdt_district_name','-- Select District --');
            var distSel=document.getElementById('tr_district_id'); if(distSel){ distSel.value=districtId; }
            document.getElementById('tr_district').value=distName;
            if(!districtId){ showLocLoading(false); return; }
            ajaxPost(BASE_URL+'get_mandals',{district_id:districtId},function(res3){
                showLocLoading(false);
                populateSelect('tr_mandal_id',res3.data||[],'tm_mandal_ID','tm_mandal','-- Select Mandal --');
                if(mandalName){ var mandalSel=document.getElementById('tr_mandal_id'); if(mandalSel){ for(var i=0;i<mandalSel.options.length;i++){ if(mandalSel.options[i].text===mandalName){ mandalSel.selectedIndex=i; break; } } } document.getElementById('tr_mandal').value=mandalName; }
            },function(){ showLocLoading(false); });
        },function(){ showLocLoading(false); });
    }

    // ── OPEN MODAL ──
    function openModal(tr_id){
        resetForm();
        if(tr_id){
            document.getElementById('modalTitle').textContent='Edit Registration';
            document.getElementById('tr_id').value=tr_id;
            lockAadhar(true);
            ajaxPost(BASE_URL+'get_registration',{tr_id:tr_id},function(res){
                var r=res.data; if(!r) return;
                var fields=['tr_mobile','tr_language','tr_registration_type','tr_aadhar_no','tr_full_name','tr_pan_no','tr_dob','tr_full_address','tr_pincode','tr_status','tr_email'];
                for(var i=0;i<fields.length;i++){ var f=fields[i],el=document.getElementById(f); if(el&&r[f]!==undefined&&r[f]!==null) el.value=r[f]; }
                document.getElementById('tr_mobile')._origVal=r['tr_mobile']||'';
                document.getElementById('tr_aadhar_no')._origVal=r['tr_aadhar_no']||'';
                restoreLocation(r);
                var docMap={'tr_selfie':{view:'view_f_selfie',prev:'prev_f_selfie'},'tr_pan_copy':{view:'view_f_pan',prev:'prev_f_pan'},'tr_aadhar_front':{view:'view_f_adf',prev:'prev_f_adf'},'tr_aadhar_back':{view:'view_f_adb',prev:'prev_f_adb'},'tr_transport_front':{view:'view_f_trf',prev:'prev_f_trf'},'tr_transport_back':{view:'view_f_trb',prev:'prev_f_trb'}};
                for(var field in docMap){
                    if(!docMap.hasOwnProperty(field)) continue;
                    var urlKey=field+'_url';
                    if(r[urlKey]){
                        var viewEl=document.getElementById(docMap[field].view); var prevEl=document.getElementById(docMap[field].prev);
                        if(viewEl){ viewEl.setAttribute('data-url',r[urlKey]); viewEl.classList.remove('d-none'); }
                        if(prevEl&&/\.(jpg|jpeg|png)$/i.test(r[urlKey])){ prevEl.src=r[urlKey]; prevEl.classList.remove('d-none'); }
                    }
                }
            });
        } else {
            document.getElementById('modalTitle').textContent='Add Registration';
            lockAadhar(false);
            document.getElementById('tr_mobile')._origVal='';
            document.getElementById('tr_aadhar_no')._origVal='';
        }
        $('#regModal').modal('show');
    }

    function resetForm(){
        document.getElementById('regForm').reset();
        document.getElementById('tr_id').value='';
        document.getElementById('reg_csrf_field').value=Csrf.get();
        hideFormAlert();
        var errEls=document.querySelectorAll('#regForm .field-error');
        for(var i=0;i<errEls.length;i++) errEls[i].classList.remove('field-error');
        var errMsgs=document.querySelectorAll('#regForm .field-error-msg');
        for(var i=0;i<errMsgs.length;i++) if(errMsgs[i].parentNode) errMsgs[i].parentNode.removeChild(errMsgs[i]);
        clearFieldState('tr_mobile'); clearFieldState('tr_aadhar_no');
        document.getElementById('mobile_msg').className='field-msg'; document.getElementById('mobile_msg').textContent='';
        document.getElementById('aadhar_msg').className='field-msg'; document.getElementById('aadhar_msg').textContent='';
        document.getElementById('pan_msg').className='field-msg';    document.getElementById('pan_msg').textContent='';
        lockAadhar(false);
        document.getElementById('aadhar_unlock_row').classList.add('d-none');
        document.getElementById('aadhar_unlock_warn').classList.remove('show');
        resetSelect('tr_state_id','-- Select State --'); resetSelect('tr_district_id','-- Select District --'); resetSelect('tr_mandal_id','-- Select Mandal --');
        ['tr_country','tr_state','tr_district','tr_mandal'].forEach(function(id){ var el=document.getElementById(id); if(el) el.value=''; });
        var els=document.querySelectorAll('[id^="prev_"],[id^="view_"]');
        for(var i=0;i<els.length;i++){ els[i].classList.add('d-none'); if(els[i].tagName==='IMG') els[i].src=''; if(els[i].tagName==='BUTTON') els[i].setAttribute('data-url',''); }
        var docBoxes=document.querySelectorAll('.doc-upload-box');
        for(var i=0;i<docBoxes.length;i++) docBoxes[i].classList.remove('doc-required','field-error');
        var reqMsgs=document.querySelectorAll('.doc-req-msg');
        for(var i=0;i<reqMsgs.length;i++) reqMsgs[i].classList.remove('show');
    }

    // ── ALERTS ──
    function showFormAlert(msg,type,alertId){ alertId=alertId||'formAlert'; var el=document.getElementById(alertId); if(!el) return; el.textContent=msg; el.className='form-alert alert-'+(type==='success'?'success':'error'); el.style.display='block'; if(type==='success') setTimeout(function(){ hideFormAlert(alertId); },4000); }
    function hideFormAlert(alertId){ alertId=alertId||'formAlert'; var el=document.getElementById(alertId); if(el){ el.style.display='none'; el.textContent=''; } }

    // ── PAN LIVE FORMAT CHECK ──
    var PAN_REGEX = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
    function panLiveCheck(inp){
        var val = inp.value.trim();
        var msgEl = document.getElementById('pan_msg');
        inp.classList.remove('field-error');
        var n = inp.parentNode ? inp.parentNode.querySelector('.field-error-msg') : null;
        if(n && n.parentNode) n.parentNode.removeChild(n);
        if(!val){ msgEl.className='field-msg'; msgEl.textContent=''; return; }
        if(val.length < 10){ msgEl.className='field-msg'; msgEl.textContent=''; return; }
        if(PAN_REGEX.test(val)){
            msgEl.className='field-msg show-ok'; msgEl.innerHTML='&#10003; Valid PAN format';
        } else {
            msgEl.className='field-msg show-err'; msgEl.innerHTML='&#9888; Invalid format — must be like ABCDE1234F';
        }
    }

    // ── FIELD ERROR HELPER ──
    function _fail(el, msg, isSelect, hasErrorRef, firstErrRef) {
        hasErrorRef[0] = true;
        if (!firstErrRef[0]) firstErrRef[0] = el;
        if (!el) return;
        el.classList.add('field-error');
        var wrap = el.parentNode;
        var n = wrap ? wrap.querySelector('.field-error-msg') : null;
        if (!n && wrap) { n = document.createElement('div'); n.className='field-error-msg'; wrap.appendChild(n); }
        if (n) n.textContent = msg || '';
        var ev = isSelect ? 'change' : 'input';
        function clr(){ el.classList.remove('field-error'); if(n&&n.parentNode) n.parentNode.removeChild(n); el.removeEventListener(ev,clr); el.removeEventListener('change',clr); }
        el.addEventListener(ev, clr);
        if(!isSelect) el.addEventListener('change', clr);
    }
    function clearAllFieldErrors(){
        var els=document.querySelectorAll('#regForm .field-error');
        for(var i=0;i<els.length;i++) els[i].classList.remove('field-error');
        var msgs=document.querySelectorAll('#regForm .field-error-msg');
        for(var i=0;i<msgs.length;i++) if(msgs[i].parentNode) msgs[i].parentNode.removeChild(msgs[i]);
    }

    // ── SAVE REGISTRATION ──
    function saveRegistration(){
        var btn = document.getElementById('saveBtn');
        hideFormAlert();
        clearAllFieldErrors();

        var mobileEl  = document.getElementById('tr_mobile');
        var langEl    = document.getElementById('tr_language');
        var rtypeEl   = document.getElementById('tr_registration_type');
        var emailEl   = document.getElementById('tr_email');
        var nameEl    = document.getElementById('tr_full_name');
        var panEl     = document.getElementById('tr_pan_no');
        var aadharEl  = document.getElementById('tr_aadhar_no');
        var dobEl     = document.getElementById('tr_dob');
        var addrEl    = document.getElementById('tr_full_address');
        var stateEl   = document.getElementById('tr_state_id');
        var distEl    = document.getElementById('tr_district_id');
        var mandalEl  = document.getElementById('tr_mandal_id');
        var villageEl = document.getElementById('tr_village');
        var pincodeEl = document.getElementById('tr_pincode');

        var mobile   = mobileEl  ? mobileEl.value.trim()  : '';
        var lang     = langEl    ? langEl.value            : '';
        var rtype    = rtypeEl   ? rtypeEl.value           : '';
        var email    = emailEl   ? emailEl.value.trim()    : '';
        var fullName = nameEl    ? nameEl.value.trim()     : '';
        var pan      = panEl     ? panEl.value.trim()      : '';
        var aadhar   = aadharEl  ? aadharEl.value.trim()  : '';
        var dob      = dobEl     ? dobEl.value             : '';
        var address  = addrEl    ? addrEl.value.trim()     : '';
        var stateId  = stateEl   ? stateEl.value           : '';
        var distId   = distEl    ? distEl.value            : '';
        var mandalId = mandalEl  ? mandalEl.value          : '';
        var village  = villageEl ? villageEl.value.trim()  : '';
        var pincode  = pincodeEl ? pincodeEl.value.trim()  : '';

        var hasErr = [false];
        var firstEl = [null];
        function fail(el, msg, isSel){ _fail(el, msg, isSel||false, hasErr, firstEl); }

        if (!mobile || mobile.length !== 10 || isNaN(mobile))
            fail(mobileEl, 'Must be exactly 10 digits');
        if (!lang)
            fail(langEl, 'Please select a language', true);
        if (!rtype)
            fail(rtypeEl, 'Please select a type', true);
        if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/))
            fail(emailEl, 'Valid email address required');
        if (!fullName)
            fail(nameEl, 'Full name is required');
        if (!pan)
            fail(panEl, 'PAN card number is required');
        else if (!PAN_REGEX.test(pan))
            fail(panEl, 'Invalid format — must be like ABCDE1234F (5 letters, 4 digits, 1 letter)');
        if (!aadhar || aadhar.length !== 12)
            fail(aadharEl, 'Must be exactly 12 digits');
        if (!dob)
            fail(dobEl, 'Date of birth is required');
        if (!address)
            fail(addrEl, 'Full address is required');
        if (!stateId)
            fail(stateEl, 'Please select a state', true);
        if (!distId)
            fail(distEl, 'Please select a district', true);
        if (!mandalId)
            fail(mandalEl, 'Please select a mandal', true);
        if (!village)
            fail(villageEl, 'City / Village is required');
        if (!pincode || pincode.length !== 6 || isNaN(pincode))
            fail(pincodeEl, 'Must be exactly 6 digits');

        if (hasErr[0]) {
            showFormAlert('Please fill all required fields highlighted in red.', 'error');
            if (firstEl[0]) {
                firstEl[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(function(){ try{ firstEl[0].focus(); }catch(e){} }, 350);
            }
            return;
        }

        var isAdd = !document.getElementById('tr_id').value;
        if (isAdd) {
            var docRequired = [
                {id:'f_selfie',label:'Selfie'},{id:'f_pan',label:'PAN Copy'},
                {id:'f_adf',label:'Aadhar Front'},{id:'f_adb',label:'Aadhar Back'},
                {id:'f_trf',label:'Transport/DL Front'},{id:'f_trb',label:'Transport/DL Back'}
            ];
            for(var d=0;d<docRequired.length;d++){
                var bx=document.getElementById('box_'+docRequired[d].id);
                var rm=document.getElementById('req_'+docRequired[d].id);
                if(bx){ bx.classList.remove('doc-required','field-error'); }
                if(rm) rm.classList.remove('show');
            }
            var firstMissingBox = null;
            for(var d=0;d<docRequired.length;d++){
                var fileEl=document.getElementById(docRequired[d].id);
                if(!fileEl||!fileEl.files||!fileEl.files.length){
                    var bx=document.getElementById('box_'+docRequired[d].id);
                    var rm=document.getElementById('req_'+docRequired[d].id);
                    if(bx){ bx.classList.add('doc-required','field-error'); if(!firstMissingBox) firstMissingBox=bx; }
                    if(rm) rm.classList.add('show');
                }
            }
            if(firstMissingBox){
                showFormAlert('Please upload all required documents (highlighted in red).','error');
                setTimeout(function(){ firstMissingBox.scrollIntoView({behavior:'smooth',block:'center'}); },150);
                return;
            }
        }

        if(hasDuplicateFields()) return;

        setLoading(btn, true);
        var fd = new FormData(document.getElementById('regForm'));
        ajaxPost(BASE_URL+'save', fd,
            function(res){ setLoading(btn,false); showFormAlert(res.message,'success'); showToast(res.message,'success'); setTimeout(function(){ $('#regModal').modal('hide'); dt.ajax.reload(null,false); },1200); },
            function(res){ setLoading(btn,false); var msg=(res&&res.message)?res.message:'An error occurred. Please try again.'; showFormAlert(msg,'error'); showToast(msg,'error'); }
        );
    }

    // ── TOGGLE STATUS ──
    function toggleStatus(tr_id,currentStatus){
        var newStatus=currentStatus==='active'?'inactive':'active';
        var color=newStatus==='active'?'#10b981':'#64748b';
        Swal.fire({ title:'Set to '+newStatus.toUpperCase()+'?', text:'Registration will be marked as '+newStatus+'.'+(newStatus==='active'?' Login credentials will be emailed.':''), icon:'question', showCancelButton:true, confirmButtonColor:color, confirmButtonText:'Yes, set '+newStatus })
            .then(function(result){
                if(!result.isConfirmed) return;
                ajaxPost(BASE_URL+'toggle_status',{tr_id:tr_id,status:newStatus},function(res){ showToast(res.message,'success'); dt.ajax.reload(null,false); });
            });
    }

    // ── DELETE ──
    function deleteRecord(tr_id){
        Swal.fire({ title:'Delete Registration?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Yes, Delete!' })
            .then(function(result){ if(!result.isConfirmed) return; ajaxPost(BASE_URL+'delete',{tr_id:tr_id},function(res){ showToast(res.message,'success'); dt.ajax.reload(null,false); }); });
    }

    // ── PASSWORD MODAL ──
    function openPwdModal(tr_id,mobile){
        document.getElementById('pwd_tr_id').value=tr_id;
        document.getElementById('pwd_mobile').textContent=mobile;
        document.getElementById('pwd_csrf_field').value=Csrf.get();
        document.getElementById('new_password').value='';
        document.getElementById('confirm_password').value='';
        hideFormAlert('pwdAlert');
        $('#pwdModal').modal('show');
    }
    function changePassword(){
        var btn=document.getElementById('pwdSaveBtn');
        var tr_id=document.getElementById('pwd_tr_id').value;
        var pwd=document.getElementById('new_password').value;
        var confirm=document.getElementById('confirm_password').value;
        hideFormAlert('pwdAlert');
        if(pwd.length<6){ showFormAlert('Password must be at least 6 characters','error','pwdAlert'); return; }
        if(pwd!==confirm){ showFormAlert('Passwords do not match','error','pwdAlert'); return; }
        setLoading(btn,true);
        ajaxPost(BASE_URL+'change_password',{tr_id:tr_id,new_password:pwd,confirm_password:confirm},
            function(res){ setLoading(btn,false); showFormAlert(res.message,'success','pwdAlert'); showToast(res.message,'success'); setTimeout(function(){ $('#pwdModal').modal('hide'); },1200); },
            function(res){ setLoading(btn,false); var msg=(res&&res.message)?res.message:'Failed to update password.'; showFormAlert(msg,'error','pwdAlert'); }
        );
    }
    function generatePwd(){
        var chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        var pwd=''; for(var i=0;i<12;i++) pwd+=chars.charAt(Math.floor(Math.random()*chars.length));
        document.getElementById('new_password').value=pwd;
        document.getElementById('confirm_password').value=pwd;
        showToast('Password generated! Copy it before saving.','info');
    }

    // ── UTILITIES ──
    function previewDoc(input,prevId,viewId){
        var file=input.files[0]; if(!file) return;
        var boxEl=document.getElementById('box_'+input.id); var reqEl=document.getElementById('req_'+input.id);
        if(boxEl) boxEl.classList.remove('doc-required'); if(reqEl) reqEl.classList.remove('show');
        if(file.type.indexOf('image/')===0){ var reader=new FileReader(); reader.onload=function(e){ var el=document.getElementById(prevId); if(el){ el.src=e.target.result; el.classList.remove('d-none'); } }; reader.readAsDataURL(file); }
        var vEl=document.getElementById(viewId); if(vEl) vEl.classList.add('d-none');
    }
    function togglePwd(inputId,btn){ var el=document.getElementById(inputId); var isText=(el.type==='text'); el.type=isText?'password':'text'; var icon=btn.querySelector('i'); if(icon) icon.className=isText?'bi bi-eye':'bi bi-eye-slash'; }
    function setLoading(btn,state){ var txt=btn.querySelector('.btn-text'); var spin=btn.querySelector('.spinner-border'); if(txt) txt.classList.toggle('d-none',state); if(spin) spin.classList.toggle('d-none',!state); btn.disabled=state; }
    function showToast(msg,type){ type=type||'success'; Swal.fire({ toast:true, position:'top-end', icon:type, title:msg, showConfirmButton:false, timer:3200, timerProgressBar:true }); }
</script>
