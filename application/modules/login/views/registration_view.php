<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TGTDA – Driver &amp; Transport Registration</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login/css2.css">
    <link href="<?php echo base_url();?>dist/css/style.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #5029bc;
            --primary-light: #063761;
            --primary-dark: #075ca6;
            --accent: #f5a623;
            --accent-dark: #d4891a;
            --surface: #f7fbf9;
            --card-bg: #ffffff;
            --text: #1a2e22;
            --text-muted: #5a7a65;
            --border: #c8e6d5;
            --error: #dc3545;
            --success: #198754;
            --step-inactive: #cde8d8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--surface);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Header ── */
        .site-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, var(--primary-light) 100%);
            padding: 18px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,.25);
            position: sticky; top: 0; z-index: 100;
        }
        .site-header .logo-text {
            font-family: 'Baloo 2', cursive;
            font-size: 1.7rem; font-weight: 800;
            color: #fff; letter-spacing: 1px;
        }
        .site-header .logo-sub { font-size: .78rem; color: #ffffff; letter-spacing: .5px; }
        .site-header .header-badge {
            background: var(--accent); color: #fff;
            font-size: .72rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: .5px;
        }

        /* ── Progress Steps ── */
        .progress-wrap { background: #fff; border-bottom: 1px solid var(--border); padding: 16px 0; }
        .steps-row { display: flex; justify-content: center; gap: 0; }
        .step-item {
            display: flex; flex-direction: column; align-items: center;
            position: relative; width: 120px;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute; top: 18px; left: calc(50% + 22px);
            width: calc(100% - 44px); height: 2px;
            background: var(--step-inactive);
            transition: background .4s;
        }
        .step-item.done:not(:last-child)::after { background: var(--primary); }
        .step-circle {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--step-inactive); color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .95rem;
            transition: all .3s; border: 3px solid transparent;
        }
        .step-item.active .step-circle {
            background: var(--primary); color: #fff;
            border-color: var(--accent); box-shadow: 0 0 0 4px rgba(245,166,35,.2);
        }
        .step-item.done .step-circle { background: var(--primary); color: #fff; }
        .step-label { font-size: .68rem; font-weight: 600; color: var(--text-muted); margin-top: 5px; text-align: center; }
        .step-item.active .step-label { color: var(--primary); font-weight: 700; }

        /* ── Card ── */
        .form-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(26,92,56,.1);
            overflow: hidden;
            max-width: 680px; margin: 36px auto;
        }
        .card-head {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 28px 32px 24px;
        }
        .card-head h2 {
            font-family: 'Baloo 2', cursive;
            font-size: 1.45rem; font-weight: 700; color: #fff; margin-bottom: 4px;
        }
        .card-head p { color: #ffffff; font-size: .85rem; }
        .card-body-wrap { padding: 32px; }

        /* ── Form Controls ── */
        .form-section { margin-bottom: 28px; }
        .section-title {
            font-size: .72rem; font-weight: 700; letter-spacing: 1.2px;
            text-transform: uppercase; color: var(--primary);
            border-left: 3px solid var(--accent); padding-left: 10px; margin-bottom: 16px;
        }
        .form-label { font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 5px; }
        .form-label .req { color: var(--error); margin-left: 2px; }

        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 10px; padding: 10px 14px;
            font-size: .9rem; font-family: 'Nunito', sans-serif;
            transition: all .2s; background: #fafffe;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,92,56,.12);
            background: #fff;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--error) !important;
            box-shadow: 0 0 0 3px rgba(220,53,69,.1) !important;
            animation: shake .3s ease;
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)}
        }

        .input-icon-wrap { position: relative; }
        .input-icon-wrap .bi { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1rem; pointer-events: none; }
        .input-icon-wrap .form-control { padding-left: 38px; }
        .input-icon-wrap .status-icon { left: auto; right: 13px; pointer-events: none; }
        .input-icon-wrap .status-icon.ok { color: var(--success); }
        .input-icon-wrap .status-icon.err { color: var(--error); }

        .invalid-feedback { font-size: .76rem; font-weight: 600; color: var(--error); display: block; margin-top: 4px; }

        /* ── Buttons ── */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none; color: #fff; font-weight: 700;
            padding: 12px 28px; border-radius: 10px;
            font-family: 'Nunito', sans-serif; font-size: .92rem;
            cursor: pointer; transition: all .2s;
            box-shadow: 0 4px 15px rgba(26,92,56,.3);
        }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,92,56,.4); }
        .btn-primary-custom:active { transform: translateY(0); }
        .btn-primary-custom:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            border: none; color: #fff; font-weight: 700;
            padding: 10px 22px; border-radius: 10px;
            font-family: 'Nunito', sans-serif; font-size: .88rem;
            cursor: pointer; transition: all .2s;
        }
        .btn-accent:hover { filter: brightness(1.08); }

        .btn-outline-custom {
            border: 2px solid var(--primary); color: var(--primary);
            background: transparent; font-weight: 700;
            padding: 9px 20px; border-radius: 10px;
            font-family: 'Nunito', sans-serif; font-size: .88rem;
            cursor: pointer; transition: all .2s;
        }
        .btn-outline-custom:hover { background: var(--primary); color: #fff; }

        /* ── OTP Box ── */
        .otp-inputs { display: flex; gap: 10px; justify-content: center; }
        .otp-inputs input {
            width: 48px; height: 54px; text-align: center;
            font-size: 1.3rem; font-weight: 700;
            border: 2px solid var(--border); border-radius: 10px;
            outline: none; transition: all .2s;
            background: #fafffe;
        }
        .otp-inputs input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,92,56,.12); }
        .otp-inputs input.filled { border-color: var(--primary); background: #f0faf5; }

        /* ── File Upload ── */
        .file-upload-box {
            border: 2px dashed var(--border); border-radius: 12px;
            padding: 18px 16px; text-align: center;
            cursor: pointer; transition: all .2s; position: relative;
            background: #fafffe;
        }
        .file-upload-box:hover { border-color: var(--primary); background: #f0faf5; }
        .file-upload-box.has-file { border-color: var(--success); background: #f0fdf5; }
        .file-upload-box.is-invalid { border-color: var(--error); background: #fff5f5; }
        .file-upload-box input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .file-upload-box .upload-icon { font-size: 1.5rem; color: var(--text-muted); margin-bottom: 4px; }
        .file-upload-box .upload-label { font-size: .78rem; font-weight: 600; color: var(--text-muted); }
        .file-upload-box .file-name { font-size: .75rem; color: var(--success); font-weight: 700; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── Document Camera Capture ── */
        .doc-field-wrap { margin-bottom: 0; }
        .doc-input-group {
            border: 1.5px solid var(--border); border-radius: 12px;
            overflow: hidden; background: #fafffe;
        }
        .doc-input-group.has-file { border-color: var(--success); background: #f0fdf5; }
        .doc-input-group.is-invalid { border-color: var(--error); background: #fff5f5; }

        .doc-tabs {
            display: flex; border-bottom: 1px solid var(--border);
            background: #f0f4ff;
        }
        .doc-tab {
            flex: 1; padding: 8px 6px; border: none; background: transparent;
            font-size: .74rem; font-weight: 700; color: var(--text-muted);
            cursor: pointer; transition: all .2s; letter-spacing: .3px;
            font-family: 'Nunito', sans-serif;
        }
        .doc-tab.active {
            background: var(--primary); color: #fff;
        }
        .doc-tab:first-child { border-radius: 0; }
        .doc-tab:last-child { border-radius: 0; }

        .doc-panel { display: none; padding: 12px; }
        .doc-panel.active { display: block; }

        /* Upload Panel */
        .doc-upload-area {
            border: 2px dashed var(--border); border-radius: 10px;
            padding: 16px 12px; text-align: center;
            cursor: pointer; transition: all .2s; position: relative;
            background: #fff;
        }
        .doc-upload-area:hover { border-color: var(--primary); background: #f0faf5; }
        .doc-upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .doc-upload-area .upload-icon { font-size: 1.4rem; }
        .doc-upload-area .upload-label { font-size: .76rem; font-weight: 600; color: var(--text-muted); margin-top: 4px; }
        .doc-upload-area .file-hint { font-size: .68rem; color: var(--text-muted); margin-top: 2px; }
        .doc-upload-area .file-name-display { font-size: .74rem; color: var(--success); font-weight: 700; margin-top: 4px; }

        /* Camera Panel */
        .doc-camera-wrap { text-align: center; }
        .doc-camera-video {
            width: 100%; max-width: 100%; border-radius: 8px;
            background: #1a1a1a; display: none;
            max-height: 220px; object-fit: cover;
        }
        .doc-camera-canvas { display: none; }
        .doc-captured-preview {
            width: 100%; border-radius: 8px; display: none;
            max-height: 160px; object-fit: contain;
            border: 2px solid var(--success);
        }
        .doc-camera-btns { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-top: 8px; }
        .doc-cam-placeholder {
            padding: 20px 12px; text-align: center;
            font-size: .78rem; color: var(--text-muted);
        }
        .doc-cam-placeholder .cam-icon { font-size: 2rem; margin-bottom: 6px; }

        /* ── Selfie / Camera ── */
        .selfie-wrap { position: relative; }
        .camera-preview {
            width: 100%; max-width: 320px; height: 240px;
            border-radius: 12px; background: #1a1a1a;
            object-fit: cover; display: none; margin: 0 auto;
        }
        .captured-img {
            width: 120px; height: 120px; border-radius: 50%;
            object-fit: cover; border: 3px solid var(--primary);
            display: none; margin: 0 auto;
        }
        .selfie-btns { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-top: 10px; }
        .camera-canvas { display: none; }

        /* ── Language Cards ── */
        .lang-cards { display: flex; gap: 10px; flex-wrap: wrap; }
        .lang-card {
            flex: 1; min-width: 90px; padding: 12px 10px; border-radius: 10px;
            border: 2px solid var(--border); text-align: center; cursor: pointer;
            transition: all .2s; background: #fafffe;
        }
        .lang-card:hover { border-color: var(--primary); background: #f0faf5; }
        .lang-card.selected { border-color: var(--primary); background: var(--primary); color: #fff; }
        .lang-card .lang-name { font-size: .82rem; font-weight: 700; }
        .lang-card .lang-native { font-size: .75rem; opacity: .8; }
        .lang-card input { display: none; }

        /* ── Type Cards ── */
        .type-cards { display: flex; gap: 12px; flex-wrap: wrap; }
        .type-card {
            flex: 1; min-width: 130px; padding: 16px 14px; border-radius: 12px;
            border: 2px solid var(--border); cursor: pointer;
            transition: all .2s; background: #fafffe;
        }
        .type-card:hover { border-color: var(--primary); }
        .type-card.selected { border-color: var(--primary); background: #f0faf5; box-shadow: 0 4px 12px rgba(26,92,56,.15); }
        .type-card .type-icon { font-size: 1.6rem; margin-bottom: 8px; }
        .type-card .type-title { font-size: .85rem; font-weight: 700; }
        .type-card .type-sub { font-size: .72rem; color: var(--text-muted); }
        .type-card input { display: none; }

        /* ── Terms Lightbox ── */
        .modal-terms .modal-header { background: var(--primary); color: #fff; }
        .modal-terms .modal-title { font-family: 'Baloo 2', cursive; font-weight: 700; }
        .modal-terms .modal-header .btn-close { filter: invert(1); }
        .terms-content { max-height: 340px; overflow-y: auto; font-size: .85rem; line-height: 1.7; color: #334; }
        .terms-content h6 { font-weight: 700; color: var(--primary); margin-top: 14px; }

        /* ── Aadhar Field ── */
        .aadhar-formatted { letter-spacing: 4px; font-size: 1rem; font-weight: 600; }

        /* ── Alert ── */
        .alert-custom {
            border-radius: 10px; padding: 12px 16px; font-size: .85rem; font-weight: 600;
            display: none; align-items: center; gap: 10px;
        }
        .alert-custom.show { display: flex; }
        .alert-danger-custom { background: #fff5f5; border: 1px solid #ffd0d0; color: var(--error); }
        .alert-success-custom { background: #f0fdf5; border: 1px solid #a8f0c8; color: var(--success); }

        /* ── Steps Visibility ── */
        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeIn .3s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* ── Success Page ── */
        .success-screen { text-align: center; padding: 48px 32px; }
        .success-icon-wrap {
            width: 90px; height: 90px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px; font-size: 2.8rem; color: #fff;
            box-shadow: 0 8px 30px rgba(26,92,56,.35);
            animation: popIn .5s cubic-bezier(.175,.885,.32,1.275);
        }
        @keyframes popIn { from{transform:scale(0)} to{transform:scale(1)} }
        .success-screen h2 { font-family: 'Baloo 2', cursive; font-size: 1.6rem; color: var(--primary); margin-bottom: 8px; }
        .success-screen p { color: var(--text-muted); font-size: .9rem; }
        .reg-id-badge {
            display: inline-block; background: var(--primary); color: #fff;
            font-size: 1.1rem; font-weight: 800; padding: 8px 24px;
            border-radius: 50px; margin: 16px 0; letter-spacing: 2px;
        }

        /* ── Spinner ── */
        .spinner-wrap { display: none; align-items: center; gap: 8px; color: var(--text-muted); font-size: .82rem; }
        .spinner-wrap.show { display: flex; }

        /* ── Camera Modal Overlay ── */
        .cam-modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.85); align-items: center; justify-content: center;
        }
        .cam-modal-overlay.show { display: flex; }
        .cam-modal-box {
            background: #fff; border-radius: 16px; width: 96vw; max-width: 480px;
            overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.5);
        }
        .cam-modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;
        }
        .cam-modal-header h6 { color: #fff; font-weight: 700; margin: 0; font-size: .95rem; }
        .cam-modal-close {
            background: rgba(255,255,255,.2); border: none; color: #fff;
            width: 30px; height: 30px; border-radius: 50%; cursor: pointer;
            font-size: 1rem; display: flex; align-items: center; justify-content: center;
        }
        .cam-modal-body { padding: 16px; }
        .cam-modal-video {
            width: 100%; border-radius: 10px; background: #1a1a1a;
            max-height: 300px; object-fit: cover; display: block;
        }
        .cam-modal-canvas { display: none; }
        .cam-modal-preview {
            width: 100%; border-radius: 10px; display: none;
            max-height: 260px; object-fit: contain;
            border: 2px solid var(--success); margin-top: 8px;
        }
        .cam-modal-footer {
            padding: 12px 16px; display: flex; gap: 10px; justify-content: center;
            border-top: 1px solid var(--border);
        }
        .cam-switch-btn {
            background: #e8f0fe; border: none; color: var(--primary);
            padding: 6px 14px; border-radius: 8px; font-size: .78rem;
            font-weight: 700; cursor: pointer; font-family: 'Nunito', sans-serif;
        }
        .cam-switch-btn:hover { background: #d0e0ff; }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .card-body-wrap { padding: 20px 16px; }
            .otp-inputs input { width: 40px; height: 46px; font-size: 1.1rem; }
            .step-label { font-size: .6rem; }
            .steps-row { gap: 0; }
            .step-item { width: 90px; }
            .doc-tab { font-size: .68rem; padding: 8px 4px; }
        }

        .modal-open { overflow: auto !important; }
    </style>
</head>
<body>

<!-- ═══ HEADER ═══ -->
<header class="site-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="images/logo.jpg" alt="TGTDA Logo" style="height:60px; margin-right:10px;">
                <div>
                    <div class="logo-text">TGTDA</div>
                    <div class="logo-sub">TELANGANA GOODS TRANSPORT AND DRIVERS ASSOCIATION</div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ═══ PROGRESS STEPS ═══ -->
<div class="progress-wrap">
    <div class="container">
        <div class="steps-row">
            <div class="step-item active" id="step-ind-1">
                <div class="step-circle"><i class="bi bi-phone"></i></div>
                <div class="step-label">Mobile</div>
            </div>
            <div class="step-item" id="step-ind-2">
                <div class="step-circle"><i class="bi bi-shield-lock"></i></div>
                <div class="step-label">OTP</div>
            </div>
            <div class="step-item" id="step-ind-3">
                <div class="step-circle"><i class="bi bi-person-vcard"></i></div>
                <div class="step-label">Details</div>
            </div>
            <div class="step-item" id="step-ind-4">
                <div class="step-circle"><i class="bi bi-file-earmark-arrow-up"></i></div>
                <div class="step-label">Documents</div>
            </div>
            <div class="step-item" id="step-ind-5">
                <div class="step-circle"><i class="bi bi-check-circle"></i></div>
                <div class="step-label">Done</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MAIN CARD ═══ -->
<div class="container pb-5">
    <div class="form-card">

        <!-- ───── STEP 1: Mobile ───── -->
        <div class="form-step active" id="step-1">
            <div class="card-head">
                <h2><i class="bi bi-phone-vibrate me-2"></i>Mobile Verification</h2>
                <p>Enter your 10-digit Indian mobile number to get started</p>
            </div>
            <div class="card-body-wrap">
                <div class="text-center mb-4">
                    <div style="width:70px;height:70px;background:linear-gradient(135deg,#e8f5ee,#c8e6d5);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.8rem;">📱</div>
                    <p style="font-size:.88rem;color:var(--text-muted);">We'll send a 6-digit OTP to verify your mobile number</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-phone"></i>
                        <input type="tel" class="form-control" id="mobile_no" maxlength="10"
                                placeholder="Enter 10-digit mobile number" autocomplete="off">
                        <i class="bi status-icon" id="mobile-status-icon"></i>
                    </div>
                    <div class="invalid-feedback" id="mobile-error"></div>
                    <div class="spinner-wrap mt-2" id="mobile-spinner">
                        <div class="spinner-border spinner-border-sm text-success"></div> Checking...
                    </div>
                </div>
                <div class="alert-custom alert-danger-custom" id="mobile-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span id="mobile-alert-msg"></span>
                </div>
                <div class="d-grid mt-4">
                    <button class="btn-primary-custom" id="btn-send-otp" onclick="sendOTP()">
                        <i class="bi bi-send me-2"></i>Send OTP
                    </button>
                </div>
                <p class="text-center mt-3" style="font-size:.76rem;color:var(--text-muted);">
                    <i class="bi bi-lock-fill me-1"></i>Your data is secure and encrypted
                </p>
            </div>
        </div>

        <!-- ───── STEP 2: OTP ───── -->
        <div class="form-step" id="step-2">
            <div class="card-head">
                <h2><i class="bi bi-shield-lock me-2"></i>OTP Verification</h2>
                <p>Enter the 6-digit code sent to your mobile</p>
            </div>
            <div class="card-body-wrap">
                <div class="text-center mb-4">
                    <div style="font-size:2.5rem;margin-bottom:8px;">🔐</div>
                    <p style="font-size:.9rem;font-weight:600;">OTP sent to <span id="otp-mobile-display" style="color:var(--primary)"></span></p>
                    <p style="font-size:.78rem;color:var(--text-muted);">Valid for 10 minutes</p>
                </div>
                <label class="form-label text-center d-block">Enter OTP <span class="req">*</span></label>
                <div class="otp-inputs mb-3" id="otp-boxes">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="0">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="1">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="2">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="3">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="4">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" data-index="5">
                </div>
                <div class="alert-custom alert-danger-custom mb-3" id="otp-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span id="otp-alert-msg"></span>
                </div>
                <div class="d-grid mb-3">
                    <button class="btn-primary-custom" id="btn-verify-otp" onclick="verifyOTP()">
                        <i class="bi bi-check-circle me-2"></i>Verify OTP
                    </button>
                </div>
                <div class="text-center">
                    <span style="font-size:.82rem;color:var(--text-muted);">Didn't receive? </span>
                    <button class="btn-outline-custom" style="padding:5px 14px;font-size:.78rem;" onclick="resendOTP()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Resend OTP
                    </button>
                </div>
                <div class="mt-3 p-3" style="background:#fffbf0;border-radius:10px;border:1px dashed var(--accent);">
                    <p style="font-size:.75rem;color:#8a6d00;margin:0;"><i class="bi bi-info-circle me-1"></i><strong>Demo Mode:</strong> OTP is shown in browser console / alert. Remove in production.</p>
                </div>
            </div>
        </div>

        <!-- ───── STEP 3: Registration Details ───── -->
        <div class="form-step" id="step-3">
            <div class="card-head">
                <h2><i class="bi bi-person-fill me-2"></i>Registration Details</h2>
                <p>Fill in your personal and document information</p>
            </div>
            <div class="card-body-wrap">
                <!-- Language -->
                <div class="form-section">
                    <div class="section-title">Select Language</div>
                    <div class="lang-cards" id="lang-cards">
                        <label class="lang-card selected" onclick="selectLang(this,'HINDI')">
                            <input type="radio" name="language" value="HINDI" checked>
                            <div class="lang-name">Hindi</div><div class="lang-native">हिन्दी</div>
                        </label>
                        <label class="lang-card" onclick="selectLang(this,'TELUGU')">
                            <input type="radio" name="language" value="TELUGU">
                            <div class="lang-name">Telugu</div><div class="lang-native">తెలుగు</div>
                        </label>
                        <label class="lang-card" onclick="selectLang(this,'ENGLISH')">
                            <input type="radio" name="language" value="ENGLISH">
                            <div class="lang-name">English</div><div class="lang-native">English</div>
                        </label>
                    </div>
                    <div class="invalid-feedback" id="lang-error"></div>
                </div>
                <!-- Registration Type -->
                <div class="form-section">
                    <div class="section-title">Registration Type</div>
                    <div class="type-cards">
                        <label class="type-card selected" onclick="selectType(this,'TRANSPORT')">
                            <input type="radio" name="registration_type" value="TRANSPORT" checked>
                            <div class="type-icon">🚛</div>
                            <div class="type-title">Transport</div>
                            <div class="type-sub">Commercial vehicle owner</div>
                        </label>
                        <label class="type-card" onclick="selectType(this,'DRIVER')">
                            <input type="radio" name="registration_type" value="DRIVER">
                            <div class="type-icon">🚗</div>
                            <div class="type-title">Driver</div>
                            <div class="type-sub">Professional driver</div>
                        </label>
                    </div>
                    <div class="invalid-feedback" id="type-error"></div>
                </div>
                <!-- Aadhar Number -->
                <div class="form-section">
                    <div class="section-title">Aadhar Details</div>
                    <div class="mb-3">
                        <label class="form-label">Aadhar Number <span class="req">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" class="form-control aadhar-formatted" id="aadhar_no"
                                    placeholder="XXXX  XXXX  XXXX" maxlength="16" autocomplete="off">
                            <i class="bi status-icon" id="aadhar-status-icon"></i>
                        </div>
                        <div class="invalid-feedback" id="aadhar-error"></div>
                        <div class="spinner-wrap mt-2" id="aadhar-spinner">
                            <div class="spinner-border spinner-border-sm text-success"></div> Checking...
                        </div>
                    </div>
                </div>
                <!-- Terms & Conditions -->
                <div class="form-section">
                    <div class="section-title">Terms &amp; Conditions</div>
                    <div class="p-3" style="border:1.5px solid var(--border);border-radius:12px;background:#fafffe;">
                        <div class="form-check">
                            <input class="form-check-input" disabled type="checkbox" id="terms_check">
                            <label class="form-check-label" for="terms_check" style="font-size:.85rem;">
                                I have read and agree to the
                                <button type="button" class="btn btn-link p-0" style="font-size:.85rem;color:var(--primary);font-weight:700;" onclick="showTerms()">
                                    Terms &amp; Conditions
                                </button>
                            </label>
                        </div>
                        <div class="invalid-feedback" id="terms-error"></div>
                    </div>
                </div>
                <div class="d-flex gap-3 mt-4">
                    <button class="btn-outline-custom" onclick="goStep(2)"><i class="bi bi-arrow-left me-1"></i>Back</button>
                    <button class="btn-primary-custom flex-grow-1" onclick="goStep(4)">
                        Next: Upload Documents <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ───── STEP 4: Documents ───── -->
        <div class="form-step" id="step-4">
            <div class="card-head">
                <h2><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Documents</h2>
                <p>Upload or capture each document. Accepted: JPG, PNG, PDF (max 5MB each)</p>
            </div>
            <div class="card-body-wrap">

                <!-- Selfie -->
                <div class="form-section">
                    <div class="section-title">Capture Selfie</div>
                    <div class="selfie-wrap">
                        <video id="camera-preview" class="camera-preview" autoplay playsinline></video>
                        <canvas id="camera-canvas" class="camera-canvas"></canvas>
                        <img id="captured-img" class="captured-img" src="" alt="Selfie">
                        <input type="hidden" id="selfie_data" name="selfie_captured">
                        <div class="selfie-btns">
                            <button type="button" class="btn-primary-custom" id="btn-open-camera" onclick="openCamera()">
                                <i class="bi bi-camera me-1"></i>Open Camera
                            </button>
                            <button type="button" class="btn-accent" id="btn-capture" onclick="capturePhoto()" style="display:none;">
                                <i class="bi bi-camera-fill me-1"></i>Capture
                            </button>
                            <button type="button" class="btn-outline-custom" id="btn-retake" onclick="retakePhoto()" style="display:none;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Retake
                            </button>
                        </div>
                        <div class="mt-2 text-center" style="font-size:.76rem;color:var(--text-muted);">— OR upload manually —</div>
                        <div class="file-upload-box mt-2" id="selfie-upload-box">
                            <input type="file" id="selfie_file" accept=".jpg,.jpeg,.png" onchange="handleSelfieUpload(this)">
                            <div class="upload-icon">🤳</div>
                            <div class="upload-label">Upload Selfie (JPG/PNG only)</div>
                            <div class="file-name" id="selfie-file-name"></div>
                        </div>
                        <div class="invalid-feedback" id="selfie-error"></div>
                    </div>
                </div>

                <!-- Documents Grid — each with Upload + Camera tabs -->
                <div class="form-section">
                    <div class="section-title">Identity Documents</div>
                    <div class="row g-3">

                        <!-- Aadhar Front -->
                        <div class="col-sm-6">
                            <label class="form-label">Aadhar – Front <span class="req">*</span></label>
                            <div class="doc-input-group" id="grp-aadhar-front">
                                <div class="doc-tabs">
                                    <button class="doc-tab active" onclick="switchDocTab('aadhar-front','upload',this)">
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                    <button class="doc-tab" onclick="switchDocTab('aadhar-front','camera',this)">
                                        <i class="bi bi-camera me-1"></i>Camera
                                    </button>
                                </div>
                                <div class="doc-panel active" id="panel-aadhar-front-upload">
                                    <div class="doc-upload-area">
                                        <input type="file" id="aadhar_front" accept=".jpg,.jpeg,.png,.pdf" onchange="handleDocUpload(this,'aadhar-front')">
                                        <div class="upload-icon">🪪</div>
                                        <div class="upload-label">Front Side</div>
                                        <div class="file-hint">JPG / PNG / PDF • max 5MB</div>
                                        <div class="file-name-display" id="fn-aadhar-front"></div>
                                    </div>
                                </div>
                                <div class="doc-panel" id="panel-aadhar-front-camera">
                                    <div class="doc-cam-placeholder" id="placeholder-aadhar-front">
                                        <div class="cam-icon">📷</div>
                                        <div style="font-size:.76rem;font-weight:600;">Tap to open camera</div>
                                        <button type="button" class="btn-primary-custom mt-2" style="padding:8px 16px;font-size:.78rem;" onclick="openDocCamera('aadhar-front','Aadhar Front')">
                                            <i class="bi bi-camera me-1"></i>Open Camera
                                        </button>
                                    </div>
                                    <img id="doc-preview-aadhar-front" class="doc-captured-preview" src="" alt="">
                                    <input type="hidden" id="docdata-aadhar-front">
                                    <div class="doc-camera-btns" id="doc-btns-aadhar-front" style="display:none;">
                                        <button type="button" class="btn-outline-custom" style="padding:7px 14px;font-size:.78rem;" onclick="retakeDocPhoto('aadhar-front','Aadhar Front')">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="aadhar-front-error"></div>
                        </div>

                        <!-- Aadhar Back -->
                        <div class="col-sm-6">
                            <label class="form-label">Aadhar – Back <span class="req">*</span></label>
                            <div class="doc-input-group" id="grp-aadhar-back">
                                <div class="doc-tabs">
                                    <button class="doc-tab active" onclick="switchDocTab('aadhar-back','upload',this)">
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                    <button class="doc-tab" onclick="switchDocTab('aadhar-back','camera',this)">
                                        <i class="bi bi-camera me-1"></i>Camera
                                    </button>
                                </div>
                                <div class="doc-panel active" id="panel-aadhar-back-upload">
                                    <div class="doc-upload-area">
                                        <input type="file" id="aadhar_back" accept=".jpg,.jpeg,.png,.pdf" onchange="handleDocUpload(this,'aadhar-back')">
                                        <div class="upload-icon">🪪</div>
                                        <div class="upload-label">Back Side</div>
                                        <div class="file-hint">JPG / PNG / PDF • max 5MB</div>
                                        <div class="file-name-display" id="fn-aadhar-back"></div>
                                    </div>
                                </div>
                                <div class="doc-panel" id="panel-aadhar-back-camera">
                                    <div class="doc-cam-placeholder" id="placeholder-aadhar-back">
                                        <div class="cam-icon">📷</div>
                                        <div style="font-size:.76rem;font-weight:600;">Tap to open camera</div>
                                        <button type="button" class="btn-primary-custom mt-2" style="padding:8px 16px;font-size:.78rem;" onclick="openDocCamera('aadhar-back','Aadhar Back')">
                                            <i class="bi bi-camera me-1"></i>Open Camera
                                        </button>
                                    </div>
                                    <img id="doc-preview-aadhar-back" class="doc-captured-preview" src="" alt="">
                                    <input type="hidden" id="docdata-aadhar-back">
                                    <div class="doc-camera-btns" id="doc-btns-aadhar-back" style="display:none;">
                                        <button type="button" class="btn-outline-custom" style="padding:7px 14px;font-size:.78rem;" onclick="retakeDocPhoto('aadhar-back','Aadhar Back')">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="aadhar-back-error"></div>
                        </div>

                        <!-- Transport/Licence Front -->
                        <div class="col-sm-6">
                            <label class="form-label">Transport/Driving Licence – Front <span class="req">*</span></label>
                            <div class="doc-input-group" id="grp-transport-front">
                                <div class="doc-tabs">
                                    <button class="doc-tab active" onclick="switchDocTab('transport-front','upload',this)">
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                    <button class="doc-tab" onclick="switchDocTab('transport-front','camera',this)">
                                        <i class="bi bi-camera me-1"></i>Camera
                                    </button>
                                </div>
                                <div class="doc-panel active" id="panel-transport-front-upload">
                                    <div class="doc-upload-area">
                                        <input type="file" id="transport_front" accept=".jpg,.jpeg,.png,.pdf" onchange="handleDocUpload(this,'transport-front')">
                                        <div class="upload-icon">🪪</div>
                                        <div class="upload-label">Front Side</div>
                                        <div class="file-hint">JPG / PNG / PDF • max 5MB</div>
                                        <div class="file-name-display" id="fn-transport-front"></div>
                                    </div>
                                </div>
                                <div class="doc-panel" id="panel-transport-front-camera">
                                    <div class="doc-cam-placeholder" id="placeholder-transport-front">
                                        <div class="cam-icon">📷</div>
                                        <div style="font-size:.76rem;font-weight:600;">Tap to open camera</div>
                                        <button type="button" class="btn-primary-custom mt-2" style="padding:8px 16px;font-size:.78rem;" onclick="openDocCamera('transport-front','Licence Front')">
                                            <i class="bi bi-camera me-1"></i>Open Camera
                                        </button>
                                    </div>
                                    <img id="doc-preview-transport-front" class="doc-captured-preview" src="" alt="">
                                    <input type="hidden" id="docdata-transport-front">
                                    <div class="doc-camera-btns" id="doc-btns-transport-front" style="display:none;">
                                        <button type="button" class="btn-outline-custom" style="padding:7px 14px;font-size:.78rem;" onclick="retakeDocPhoto('transport-front','Licence Front')">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="transport-front-error"></div>
                        </div>

                        <!-- Transport/Licence Back -->
                        <div class="col-sm-6">
                            <label class="form-label">Transport/Driving Licence – Back <span class="req">*</span></label>
                            <div class="doc-input-group" id="grp-transport-back">
                                <div class="doc-tabs">
                                    <button class="doc-tab active" onclick="switchDocTab('transport-back','upload',this)">
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                    <button class="doc-tab" onclick="switchDocTab('transport-back','camera',this)">
                                        <i class="bi bi-camera me-1"></i>Camera
                                    </button>
                                </div>
                                <div class="doc-panel active" id="panel-transport-back-upload">
                                    <div class="doc-upload-area">
                                        <input type="file" id="transport_back" accept=".jpg,.jpeg,.png,.pdf" onchange="handleDocUpload(this,'transport-back')">
                                        <div class="upload-icon">🪪</div>
                                        <div class="upload-label">Back Side</div>
                                        <div class="file-hint">JPG / PNG / PDF • max 5MB</div>
                                        <div class="file-name-display" id="fn-transport-back"></div>
                                    </div>
                                </div>
                                <div class="doc-panel" id="panel-transport-back-camera">
                                    <div class="doc-cam-placeholder" id="placeholder-transport-back">
                                        <div class="cam-icon">📷</div>
                                        <div style="font-size:.76rem;font-weight:600;">Tap to open camera</div>
                                        <button type="button" class="btn-primary-custom mt-2" style="padding:8px 16px;font-size:.78rem;" onclick="openDocCamera('transport-back','Licence Back')">
                                            <i class="bi bi-camera me-1"></i>Open Camera
                                        </button>
                                    </div>
                                    <img id="doc-preview-transport-back" class="doc-captured-preview" src="" alt="">
                                    <input type="hidden" id="docdata-transport-back">
                                    <div class="doc-camera-btns" id="doc-btns-transport-back" style="display:none;">
                                        <button type="button" class="btn-outline-custom" style="padding:7px 14px;font-size:.78rem;" onclick="retakeDocPhoto('transport-back','Licence Back')">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="transport-back-error"></div>
                        </div>

                        <!-- PAN Card -->
                        <div class="col-sm-6">
                            <label class="form-label">PAN Card Copy <span class="req">*</span></label>
                            <div class="doc-input-group" id="grp-pan">
                                <div class="doc-tabs">
                                    <button class="doc-tab active" onclick="switchDocTab('pan','upload',this)">
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                    <button class="doc-tab" onclick="switchDocTab('pan','camera',this)">
                                        <i class="bi bi-camera me-1"></i>Camera
                                    </button>
                                </div>
                                <div class="doc-panel active" id="panel-pan-upload">
                                    <div class="doc-upload-area">
                                        <input type="file" id="pan_copy" accept=".jpg,.jpeg,.png,.pdf" onchange="handleDocUpload(this,'pan')">
                                        <div class="upload-icon">🪪</div>
                                        <div class="upload-label">Upload PAN Copy</div>
                                        <div class="file-hint">JPG / PNG / PDF • max 5MB</div>
                                        <div class="file-name-display" id="fn-pan"></div>
                                    </div>
                                </div>
                                <div class="doc-panel" id="panel-pan-camera">
                                    <div class="doc-cam-placeholder" id="placeholder-pan">
                                        <div class="cam-icon">📷</div>
                                        <div style="font-size:.76rem;font-weight:600;">Tap to open camera</div>
                                        <button type="button" class="btn-primary-custom mt-2" style="padding:8px 16px;font-size:.78rem;" onclick="openDocCamera('pan','PAN Card')">
                                            <i class="bi bi-camera me-1"></i>Open Camera
                                        </button>
                                    </div>
                                    <img id="doc-preview-pan" class="doc-captured-preview" src="" alt="">
                                    <input type="hidden" id="docdata-pan">
                                    <div class="doc-camera-btns" id="doc-btns-pan" style="display:none;">
                                        <button type="button" class="btn-outline-custom" style="padding:7px 14px;font-size:.78rem;" onclick="retakeDocPhoto('pan','PAN Card')">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="pan-error"></div>
                        </div>

                    </div>
                </div>

                <div class="alert-custom alert-danger-custom mb-3" id="submit-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="submit-alert-msg"></span>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button class="btn-outline-custom" onclick="goStep(3)"><i class="bi bi-arrow-left me-1"></i>Back</button>
                    <button class="btn-primary-custom flex-grow-1" id="btn-submit" onclick="submitForm()">
                        <i class="bi bi-send-check me-2"></i>Submit Registration
                    </button>
                </div>

                <p class="text-center mt-3" style="font-size:.74rem;color:var(--text-muted);">
                    <i class="bi bi-info-circle me-1"></i>All fields are mandatory. Accepted formats: JPG, PNG, PDF. Max 5MB per file.
                </p>
            </div>
        </div>

        <!-- ───── STEP 5: Success ───── -->
        <div class="form-step" id="step-5">
            <div class="success-screen">
                <div class="success-icon-wrap">✅</div>
                <h2>Registration Submitted!</h2>
                <p>Your application has been received successfully.</p>
                <div class="reg-id-badge" id="reg-id-display">REG-00000</div>
                <p class="mt-2" style="font-size:.85rem;color:var(--text-muted);">
                    Our team will review your documents and send login credentials via OTP once approved.
                </p>
                <div class="mt-4 p-4" style="background:linear-gradient(135deg,#f0faf5,#e8f5ee);border-radius:16px;border:1px solid #c8e6d5;">
                    <p style="font-size:.85rem;font-weight:700;color:var(--primary);margin-bottom:8px;">
                        <i class="bi bi-clock-history me-2"></i>What happens next?
                    </p>
                    <div style="font-size:.8rem;color:var(--text-muted);text-align:left;">
                        <div class="d-flex gap-2 mb-2"><i class="bi bi-1-circle-fill" style="color:var(--primary)"></i> Documents are reviewed within 2-3 business days</div>
                        <div class="d-flex gap-2 mb-2"><i class="bi bi-2-circle-fill" style="color:var(--primary)"></i> You'll receive an SMS confirmation once approved</div>
                        <div class="d-flex gap-2"><i class="bi bi-3-circle-fill" style="color:var(--primary)"></i> Login details will be sent to your registered email.</div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="btn-primary-custom" style="text-decoration:none;">
                        <i class="bi bi-house me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>

    </div><!-- /.form-card -->
</div><!-- /.container -->

<!-- ═══ DOCUMENT CAMERA MODAL ═══ -->
<div class="cam-modal-overlay" id="docCamOverlay">
    <div class="cam-modal-box">
        <div class="cam-modal-header">
            <h6 id="docCamTitle"><i class="bi bi-camera me-2"></i>Capture Document</h6>
            <button class="cam-modal-close" onclick="closeDocCamModal()">✕</button>
        </div>
        <div class="cam-modal-body">
            <video id="docCamVideo" class="cam-modal-video" autoplay playsinline></video>
            <canvas id="docCamCanvas" class="cam-modal-canvas"></canvas>
            <img id="docCamPreview" class="cam-modal-preview" src="" alt="">
            <div class="text-center mt-2" style="font-size:.74rem;color:var(--text-muted);" id="docCamTip">
                <i class="bi bi-lightbulb me-1"></i>Place document flat on a dark surface for best results
            </div>
        </div>
        <div class="cam-modal-footer">
            <button class="cam-switch-btn" id="btnSwitchCam" onclick="switchDocCam()">
                <i class="bi bi-arrow-repeat me-1"></i>Switch Camera
            </button>
            <button class="btn-accent" id="btnDocCapture" onclick="captureDocPhoto()">
                <i class="bi bi-camera-fill me-1"></i>Capture
            </button>
            <button class="btn-outline-custom" id="btnDocRetakeModal" onclick="retakeDocModal()" style="display:none;">
                <i class="bi bi-arrow-clockwise me-1"></i>Retake
            </button>
            <button class="btn-primary-custom" id="btnDocUsePhoto" onclick="useDocPhoto()" style="display:none;">
                <i class="bi bi-check-circle me-1"></i>Use Photo
            </button>
        </div>
    </div>
</div>

<!-- ═══ TERMS & CONDITIONS MODAL ═══ -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-terms" style="background: #0d529c;">
                <h5 class="modal-title modal-terms" id="terms-modal-title" style="color: #fff;">
                    <i class="bi bi-file-text me-2"></i>Terms &amp; Conditions – TGTDA
                </h5>
            </div>
            <div class="modal-body">
                <div class="terms-content" id="terms-modal-body"></div>
            </div>
            <div class="modal-footer">
                <div class="form-check me-auto">
                    <input class="form-check-input" type="checkbox" id="modal-terms-check" onchange="syncTermsCheck(this)">
                    <label class="form-check-label fw-bold" for="modal-terms-check" id="terms-accept-label">
                        I have read and accept the Terms &amp; Conditions
                    </label>
                </div>
                <button type="button" class="btn-accent" data-bs-dismiss="modal" onclick="acceptTerms()">
                    <i class="bi bi-check-circle me-1"></i><span id="terms-accept-btn-text">Accept &amp; Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/libs/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="<?php echo base_url();?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>dist/js/app.min.js"></script>

<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    $.ajaxSetup({ data: { [csrfName]: csrfHash } });

    const BASE_URL = '<?= base_url() ?>';
    let currentStep = 1;
    let verifiedMobile = '';
    let cameraStream = null;
    let selfieBase64 = null;
    let selectedLang = 'HINDI';

    // Track doc camera captures: key → base64 string or null
    const docCaptured = {
        'aadhar-front': null,
        'aadhar-back': null,
        'transport-front': null,
        'transport-back': null,
        'pan': null
    };

    // ══════════════════════════════════════
    //  DOCUMENT CAMERA MODULE
    // ══════════════════════════════════════
    let docCamStream = null;
    let docCamFacing = 'environment'; // back camera by default for documents
    let docCamCurrentKey = null;
    let docCamCapturedData = null;

    function switchDocTab(key, tab, btn) {
        // Switch between upload/camera tabs for a document field
        const uploadPanel = document.getElementById('panel-' + key + '-upload');
        const cameraPanel = document.getElementById('panel-' + key + '-camera');
        const tabs = btn.closest('.doc-tabs').querySelectorAll('.doc-tab');
        tabs.forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        if (tab === 'upload') {
            uploadPanel.classList.add('active');
            cameraPanel.classList.remove('active');
        } else {
            uploadPanel.classList.remove('active');
            cameraPanel.classList.add('active');
        }
    }

    async function openDocCamera(key, label) {
        docCamCurrentKey = key;
        docCamCapturedData = null;
        docCamFacing = 'environment'; // start with back camera

        document.getElementById('docCamTitle').innerHTML = '<i class="bi bi-camera me-2"></i>Capture: ' + label;
        document.getElementById('docCamVideo').style.display = 'block';
        document.getElementById('docCamPreview').style.display = 'none';
        document.getElementById('btnDocCapture').style.display = '';
        document.getElementById('btnDocRetakeModal').style.display = 'none';
        document.getElementById('btnDocUsePhoto').style.display = 'none';
        document.getElementById('docCamTip').style.display = '';

        document.getElementById('docCamOverlay').classList.add('show');
        await startDocCamStream();
    }

    async function startDocCamStream() {
        if (docCamStream) { docCamStream.getTracks().forEach(t => t.stop()); }
        try {
            docCamStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: docCamFacing }, width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            document.getElementById('docCamVideo').srcObject = docCamStream;
        } catch (e) {
            // Fallback: try without facingMode constraint
            try {
                docCamStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                document.getElementById('docCamVideo').srcObject = docCamStream;
            } catch (e2) {
                closeDocCamModal();
                alert('Camera access denied. Please upload the document manually.');
            }
        }
    }

    async function switchDocCam() {
        docCamFacing = (docCamFacing === 'environment') ? 'user' : 'environment';
        await startDocCamStream();
    }

    function captureDocPhoto() {
        const video = document.getElementById('docCamVideo');
        const canvas = document.getElementById('docCamCanvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        canvas.getContext('2d').drawImage(video, 0, 0);
        docCamCapturedData = canvas.toDataURL('image/jpeg', 0.85);

        // Show preview
        const preview = document.getElementById('docCamPreview');
        preview.src = docCamCapturedData;
        preview.style.display = 'block';
        video.style.display = 'none';

        document.getElementById('btnDocCapture').style.display = 'none';
        document.getElementById('btnDocRetakeModal').style.display = '';
        document.getElementById('btnDocUsePhoto').style.display = '';
        document.getElementById('docCamTip').style.display = 'none';
    }

    function retakeDocModal() {
        docCamCapturedData = null;
        document.getElementById('docCamVideo').style.display = 'block';
        document.getElementById('docCamPreview').style.display = 'none';
        document.getElementById('btnDocCapture').style.display = '';
        document.getElementById('btnDocRetakeModal').style.display = 'none';
        document.getElementById('btnDocUsePhoto').style.display = 'none';
        document.getElementById('docCamTip').style.display = '';
    }

    function useDocPhoto() {
        if (!docCamCapturedData || !docCamCurrentKey) return;

        const key = docCamCurrentKey;
        docCaptured[key] = docCamCapturedData;

        // Show thumbnail in the camera panel
        const preview = document.getElementById('doc-preview-' + key);
        if (preview) {
            preview.src = docCamCapturedData;
            preview.style.display = 'block';
        }
        const placeholder = document.getElementById('placeholder-' + key);
        if (placeholder) placeholder.style.display = 'none';

        const btns = document.getElementById('doc-btns-' + key);
        if (btns) btns.style.display = 'flex';

        // Mark group as has-file
        const grp = document.getElementById('grp-' + key);
        if (grp) { grp.classList.add('has-file'); grp.classList.remove('is-invalid'); }

        // Clear upload-mode file if any to avoid conflict
        const fileInput = document.getElementById(key.replace(/-/g, '_'));
        if (fileInput) fileInput.value = '';

        // Clear error
        const errKey = key.replace('-front','-front').replace('-back','-back');
        const errEl = document.getElementById(errKey + '-error');
        if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }

        closeDocCamModal();
    }

    function retakeDocPhoto(key, label) {
        docCaptured[key] = null;
        const preview = document.getElementById('doc-preview-' + key);
        if (preview) { preview.src = ''; preview.style.display = 'none'; }
        const placeholder = document.getElementById('placeholder-' + key);
        if (placeholder) placeholder.style.display = '';
        const btns = document.getElementById('doc-btns-' + key);
        if (btns) btns.style.display = 'none';
        const grp = document.getElementById('grp-' + key);
        if (grp) grp.classList.remove('has-file');
        document.getElementById('docdata-' + key).value = '';
        openDocCamera(key, label);
    }

    function closeDocCamModal() {
        if (docCamStream) { docCamStream.getTracks().forEach(t => t.stop()); docCamStream = null; }
        document.getElementById('docCamOverlay').classList.remove('show');
    }

    // ══════ STEP NAVIGATION ══════
    function goStep(n, validate = true) {
        if (validate && n > currentStep) {
            if (!validateStep(currentStep)) return;
        }
        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('step-ind-' + currentStep).classList.remove('active');
        if (currentStep < n) document.getElementById('step-ind-' + currentStep).classList.add('done');
        currentStep = n;
        document.getElementById('step-' + n).classList.add('active');
        document.getElementById('step-ind-' + n).classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ══════ STEP 1 – MOBILE ══════
    $('#mobile_no').on('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 10);
        this.value = v;
        clearFieldError('mobile_no', 'mobile-error');
        $('#mobile-status-icon').removeClass('bi-check-circle-fill ok bi-x-circle-fill err');
        if (v.length === 10) debounceCheckMobile(v);
    });

    let mobileTimer;
    function debounceCheckMobile(v) {
        clearTimeout(mobileTimer);
        mobileTimer = setTimeout(() => checkMobileExists(v), 600);
    }

    function checkMobileExists(mobile) {
        if (!isValidIndianMobile(mobile)) return;
        showSpinner('mobile-spinner');
        $.post(BASE_URL + 'login/registration/check_mobile', { mobile }, function (res) {
            hideSpinner('mobile-spinner');
            if (res.exists) {
                setFieldError('mobile_no', 'mobile-error', 'This mobile number is already registered.');
                $('#mobile-status-icon').addClass('bi-x-circle-fill err');
            } else {
                $('#mobile-status-icon').addClass('bi-check-circle-fill ok');
            }
        }, 'json').fail(() => hideSpinner('mobile-spinner'));
    }

    function sendOTP() {
        const mobile = $('#mobile_no').val().trim();
        if (!validateMobileField()) return;
        $('#btn-send-otp').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
        $.post(BASE_URL + 'login/registration/send_otp', { mobile }, function (res) {
            $('#btn-send-otp').prop('disabled', false).html('<i class="bi bi-send me-2"></i>Send OTP');
            if (res.success) {
                verifiedMobile = mobile;
                $('#otp-mobile-display').text(mobile.replace(/(\d{5})(\d{5})/, '$1XXXXX'));
                alert('Enter OTP: ' + res.otp); // Remove in production
                goStep(2, false);
            } else {
                showAlert('mobile-alert', 'mobile-alert-msg', res.message || 'Failed to send OTP.');
            }
        }, 'json').fail(() => {
            $('#btn-send-otp').prop('disabled', false).html('<i class="bi bi-send me-2"></i>Send OTP');
            showAlert('mobile-alert', 'mobile-alert-msg', 'Network error. Please try again.');
        });
    }

    function validateMobileField() {
        const mobile = $('#mobile_no').val().trim();
        if (!mobile) { setFieldError('mobile_no', 'mobile-error', 'Mobile number is required.'); return false; }
        if (!isValidIndianMobile(mobile)) { setFieldError('mobile_no', 'mobile-error', 'Enter valid 10-digit Indian mobile number (starts with 6-9).'); return false; }
        if ($('#mobile-error').text()) return false;
        return true;
    }

    // ══════ STEP 2 – OTP ══════
    $(document).on('input', '.otp-digit', function () {
        this.value = this.value.replace(/\D/g, '').slice(-1);
        if (this.value) {
            $(this).addClass('filled');
            const next = parseInt($(this).data('index')) + 1;
            if (next < 6) $('.otp-digit[data-index="' + next + '"]').focus();
        } else {
            $(this).removeClass('filled');
        }
    });

    $(document).on('keydown', '.otp-digit', function (e) {
        if (e.key === 'Backspace' && !this.value) {
            const prev = parseInt($(this).data('index')) - 1;
            if (prev >= 0) $('.otp-digit[data-index="' + prev + '"]').focus();
        }
    });

    function getOTPValue() {
        return $('.otp-digit').toArray().map(i => i.value).join('');
    }

    function verifyOTP() {
        const otp = getOTPValue();
        if (otp.length < 6) { showAlert('otp-alert', 'otp-alert-msg', 'Please enter all 6 digits.'); return; }
        $('#btn-verify-otp').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Verifying...');
        $.post(BASE_URL + 'login/registration/verify_otp', { mobile: verifiedMobile, otp }, function (res) {
            $('#btn-verify-otp').prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i>Verify OTP');
            if (res.success) {
                hideAlert('otp-alert');
                goStep(3, false);
            } else {
                showAlert('otp-alert', 'otp-alert-msg', res.message || 'Invalid OTP.');
                $('.otp-digit').val('').removeClass('filled').first().focus();
            }
        }, 'json').fail(() => {
            $('#btn-verify-otp').prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i>Verify OTP');
            showAlert('otp-alert', 'otp-alert-msg', 'Network error.');
        });
    }

    function resendOTP() { sendOTP(); goStep(1, false); }

    // ══════ STEP 3 – DETAILS ══════
    function selectLang(el, val) {
        document.querySelectorAll('.lang-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        selectedLang = val;
    }

    function selectType(el, val) {
        document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
    }

    $('#aadhar_no').on('input', function () {
        let raw = this.value.replace(/\D/g, '').slice(0, 12);
        let formatted = raw.match(/.{1,4}/g)?.join('  ') || '';
        this.value = formatted;
        clearFieldError('aadhar_no', 'aadhar-error');
        $('#aadhar-status-icon').removeClass('bi-check-circle-fill ok bi-x-circle-fill err');
        if (raw.length === 12) debounceCheckAadhar(raw);
    });

    let aadharTimer;
    function debounceCheckAadhar(v) {
        clearTimeout(aadharTimer);
        aadharTimer = setTimeout(() => checkAadharExists(v), 600);
    }

    function checkAadharExists(aadhar) {
        showSpinner('aadhar-spinner');
        $.post(BASE_URL + 'login/registration/check_aadhar', { aadhar }, function (res) {
            hideSpinner('aadhar-spinner');
            if (res.exists) {
                setFieldError('aadhar_no', 'aadhar-error', 'This Aadhar number is already registered.');
                $('#aadhar-status-icon').addClass('bi-x-circle-fill err');
            } else {
                $('#aadhar-status-icon').addClass('bi-check-circle-fill ok');
            }
        }, 'json').fail(() => hideSpinner('aadhar-spinner'));
    }

    function showTerms() {
        const lang = termsData[selectedLang] || termsData['HINDI'];
        document.getElementById('terms-modal-title').innerHTML = '<i class="bi bi-file-text me-2"></i>' + lang.modalTitle;
        document.getElementById('terms-accept-label').textContent = lang.acceptLabel;
        document.getElementById('terms-accept-btn-text').textContent = lang.acceptBtn;
        let html = '';
        lang.sections.forEach(function(s) { html += '<h6>' + s.heading + '</h6><p>' + s.body + '</p>'; });
        document.getElementById('terms-modal-body').innerHTML = html;
        document.getElementById('modal-terms-check').checked = false;
        const modal = new bootstrap.Modal(document.getElementById('termsModal'));
        modal.show();
    }

    function acceptTerms() {
        if ($('#modal-terms-check').is(':checked')) {
            $('#terms_check').prop('checked', true).prop('disabled', false);
            clearFieldError('terms_check', 'terms-error');
            $(".modal,.modal-backdrop").hide();
        }
    }

    function syncTermsCheck(el) {
        if (el.checked) $('#terms_check').prop('checked', true);
    }

    // ══════ STEP 4 – SELFIE CAMERA ══════
    async function openCamera() {
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            const video = document.getElementById('camera-preview');
            video.srcObject = cameraStream;
            video.style.display = 'block';
            document.getElementById('captured-img').style.display = 'none';
            document.getElementById('btn-open-camera').style.display = 'none';
            document.getElementById('btn-capture').style.display = '';
            document.getElementById('selfie-upload-box').style.opacity = '.4';
        } catch (e) {
            alert('Camera access denied. Please upload selfie manually.');
        }
    }

    function capturePhoto() {
        const video = document.getElementById('camera-preview');
        const canvas = document.getElementById('camera-canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        selfieBase64 = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById('selfie_data').value = selfieBase64;
        const img = document.getElementById('captured-img');
        img.src = selfieBase64;
        img.style.display = 'block';
        video.style.display = 'none';
        if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
        document.getElementById('btn-capture').style.display = 'none';
        document.getElementById('btn-retake').style.display = '';
        document.getElementById('selfie-upload-box').style.opacity = '.4';
        clearFieldError(null, 'selfie-error');
    }

    function retakePhoto() {
        selfieBase64 = null;
        document.getElementById('selfie_data').value = '';
        document.getElementById('captured-img').style.display = 'none';
        document.getElementById('btn-open-camera').style.display = '';
        document.getElementById('btn-retake').style.display = 'none';
        document.getElementById('selfie-upload-box').style.opacity = '1';
    }

    function handleSelfieUpload(input) {
        const file = input.files[0];
        if (!file) return;
        const allowed = ['image/jpeg','image/jpg','image/png'];
        if (!allowed.includes(file.type)) { alert('Selfie must be JPG or PNG.'); input.value = ''; return; }
        if (file.size > 2 * 1024 * 1024) { alert('Selfie must be under 2MB.'); input.value = ''; return; }
        document.getElementById('selfie-file-name').textContent = '✓ ' + file.name;
        document.getElementById('selfie-upload-box').classList.add('has-file');
        selfieBase64 = 'file';
        clearFieldError(null, 'selfie-error');
    }

    // ══════ DOCUMENT FILE UPLOAD HANDLER ══════
    function handleDocUpload(input, key) {
        const file = input.files[0];
        if (!file) return;
        const allowed = ['image/jpeg','image/jpg','image/png','application/pdf'];
        if (!allowed.includes(file.type)) {
            alert('Only JPG, PNG, or PDF files are accepted.');
            input.value = ''; return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must not exceed 5MB.');
            input.value = ''; return;
        }
        // If uploading via file, clear camera data for this key
        docCaptured[key] = null;
        const preview = document.getElementById('doc-preview-' + key);
        if (preview) { preview.src = ''; preview.style.display = 'none'; }
        const placeholder = document.getElementById('placeholder-' + key);
        if (placeholder) placeholder.style.display = '';
        const btns = document.getElementById('doc-btns-' + key);
        if (btns) btns.style.display = 'none';

        const fnEl = document.getElementById('fn-' + key);
        if (fnEl) fnEl.textContent = '✓ ' + file.name;

        const grp = document.getElementById('grp-' + key);
        if (grp) { grp.classList.add('has-file'); grp.classList.remove('is-invalid'); }

        const errKey = key;
        const errEl = document.getElementById(errKey + '-error');
        if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }
    }

    // ══════ FORM SUBMISSION ══════
    function submitForm() {
        if (!validateStep(4)) return;
        const $btn = $('#btn-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
        const formData = new FormData();
        formData.append('language', $('input[name="language"]:checked').val());
        formData.append('registration_type', $('input[name="registration_type"]:checked').val());
        formData.append('aadhar_no', $('#aadhar_no').val().replace(/\s/g, ''));
        formData.append('terms_accepted', '1');

        // Selfie
        if (selfieBase64 && selfieBase64 !== 'file') {
            const byteString = atob(selfieBase64.split(',')[1]);
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);
            const blob = new Blob([ab], { type: 'image/jpeg' });
            formData.append('selfie', blob, 'selfie.jpg');
        } else if ($('#selfie_file')[0].files[0]) {
            formData.append('selfie', $('#selfie_file')[0].files[0]);
        }

        // Document fields — prefer camera capture, fallback to file upload
        const docFields = [
            { key: 'aadhar-front',    fileId: 'aadhar_front',    formKey: 'aadhar_front' },
            { key: 'aadhar-back',     fileId: 'aadhar_back',     formKey: 'aadhar_back' },
            { key: 'transport-front', fileId: 'transport_front', formKey: 'transport_front' },
            { key: 'transport-back',  fileId: 'transport_back',  formKey: 'transport_back' },
            { key: 'pan',             fileId: 'pan_copy',        formKey: 'pan_copy' }
        ];

        docFields.forEach(f => {
            if (docCaptured[f.key]) {
                // Camera-captured: convert base64 → blob
                try {
                    const b64 = docCaptured[f.key].split(',')[1];
                    const bytes = atob(b64);
                    const ab = new ArrayBuffer(bytes.length);
                    const ia = new Uint8Array(ab);
                    for (let i = 0; i < bytes.length; i++) ia[i] = bytes.charCodeAt(i);
                    const blob = new Blob([ab], { type: 'image/jpeg' });
                    formData.append(f.formKey, blob, f.formKey + '_captured.jpg');
                } catch(e) { /* skip */ }
            } else {
                const fileInput = document.getElementById(f.fileId);
                if (fileInput && fileInput.files[0]) {
                    formData.append(f.formKey, fileInput.files[0]);
                }
            }
        });

        formData.append(csrfName, csrfHash);

        $.ajax({
            url: BASE_URL + 'login/registration/submitReg',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="bi bi-send-check me-2"></i>Submit Registration');
                if (res.success) {
                    $('#reg-id-display').text(res.id);
                    goStep(5, false);
                } else {
                    showAlert('submit-alert', 'submit-alert-msg', res.message || 'Submission failed. Please try again.');
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-send-check me-2"></i>Submit Registration');
                showAlert('submit-alert', 'submit-alert-msg', 'Network error. Please try again.');
            }
        });
    }

    // ══════ VALIDATION ══════
    function validateStep(step) {
        let valid = true;

        if (step === 1) {
            const mobile = $('#mobile_no').val().trim();
            if (!mobile || !isValidIndianMobile(mobile)) {
                setFieldError('mobile_no', 'mobile-error', 'Enter valid 10-digit Indian mobile number.');
                valid = false;
            }
            if ($('#mobile-error').text()) valid = false;
        }

        if (step === 3) {
            const aadharRaw = $('#aadhar_no').val().replace(/\s/g, '');
            if (!aadharRaw || aadharRaw.length !== 12) {
                setFieldError('aadhar_no', 'aadhar-error', 'Enter valid 12-digit Aadhar number.');
                valid = false;
            }
            if ($('#aadhar-error').text()) valid = false;
            if (!$('#terms_check').is(':checked')) {
                $('#terms-error').text('You must accept the Terms & Conditions.').show();
                $('#terms_check').addClass('is-invalid');
                valid = false;
            }
        }

        if (step === 4) {
            // Selfie
            let hasSelfie = selfieBase64 || ($('#selfie_file')[0].files.length > 0);
            if (!hasSelfie) {
                $('#selfie-error').text('Selfie is required.').show();
                $('#selfie-upload-box').addClass('is-invalid');
                valid = false;
            }

            // Document fields
            const docChecks = [
                { key: 'pan',             fileId: 'pan_copy',        errId: 'pan-error',             label: 'PAN Card' },
                { key: 'aadhar-front',    fileId: 'aadhar_front',    errId: 'aadhar-front-error',    label: 'Aadhar Front' },
                { key: 'aadhar-back',     fileId: 'aadhar_back',     errId: 'aadhar-back-error',     label: 'Aadhar Back' },
                { key: 'transport-front', fileId: 'transport_front', errId: 'transport-front-error', label: 'Transport/Licence Front' },
                { key: 'transport-back',  fileId: 'transport_back',  errId: 'transport-back-error',  label: 'Transport/Licence Back' }
            ];

            docChecks.forEach(f => {
                const hasCamera = !!docCaptured[f.key];
                const hasFile   = document.getElementById(f.fileId)?.files.length > 0;
                if (!hasCamera && !hasFile) {
                    $('#' + f.errId).text(f.label + ' is required.').show();
                    $('#grp-' + f.key).addClass('is-invalid');
                    valid = false;
                }
            });

            if (!valid) {
                showAlert('submit-alert', 'submit-alert-msg', 'Please upload or capture all required documents.');
                const firstInvalid = document.querySelector('.doc-input-group.is-invalid, .file-upload-box.is-invalid');
                if (firstInvalid) {
                    setTimeout(() => {
                        const y = firstInvalid.getBoundingClientRect().top + window.pageYOffset - 100;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    }, 150);
                }
            }
        }

        return valid;
    }

    // ══════ HELPERS ══════
    function isValidIndianMobile(m) { return /^[6-9]\d{9}$/.test(m); }

    function setFieldError(fieldId, errId, msg) {
        if (fieldId) $('#' + fieldId).addClass('is-invalid');
        $('#' + errId).text(msg).show();
    }

    function clearFieldError(fieldId, errId) {
        if (fieldId) $('#' + fieldId).removeClass('is-invalid');
        $('#' + errId).text('').hide();
    }

    function showAlert(alertId, msgId, msg) {
        $('#' + msgId).text(msg);
        $('#' + alertId).addClass('show');
    }
    function hideAlert(alertId) { $('#' + alertId).removeClass('show'); }
    function showSpinner(id) { $('#' + id).addClass('show'); }
    function hideSpinner(id) { $('#' + id).removeClass('show'); }

    // Clear invalid state on file select
    $(document).on('change', '.doc-upload-area input[type="file"]', function () {
        $(this).closest('.doc-input-group').removeClass('is-invalid');
    });

    // Close camera modal on overlay background click
    document.getElementById('docCamOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeDocCamModal();
    });

    // OTP paste support
    $(document).on('paste', '.otp-digit', function(e) {
        e.preventDefault();
        let paste = (e.originalEvent.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
        $('.otp-digit').each(function(i) {
            $(this).val(paste[i] || '');
            if (paste[i]) $(this).addClass('filled');
        });
    });

    /* ══════════════════════════════════════
       MULTILINGUAL TERMS & CONDITIONS DATA
    ══════════════════════════════════════ */
    const termsData = {
        ENGLISH: {
            modalTitle: 'Terms & Conditions – TGTDA',
            acceptLabel: 'I have read and accept the Terms & Conditions',
            acceptBtn: 'Accept & Close',
            sections: [
                { heading: '1. Membership & Fees', body: `Associations operate on member contributions. By registering, you agree to comply with the following fee structure:<ul><li><strong>Admission Fee:</strong> One-time payment at the time of joining.</li><li><strong>Annual Subscription:</strong> Recurring fee to maintain active membership status.</li><li>Members must renew their membership on time to avoid interruption of services.</li></ul>` },
                { heading: '2. Documentation Requirements', body: `To maintain professional standards, the following documents are required:<ul><li><strong>Driver Registration:</strong> Valid Driving Licence is mandatory.</li><li><strong>Transport Registration:</strong> Must own or lease at least one commercial vehicle OR provide business registration documents.</li><li><strong>KYC Compliance:</strong> Aadhaar card and PAN card copies are required.</li></ul>` },
                { heading: '3. Welfare & Support Policy', body: `The Association does not enforce mandatory contributions for death or welfare funds. However, in case of accidental death, the Association may voluntarily raise funds to support the deceased member's family.` },
                { heading: '4. Code of Conduct', body: `Your membership may be revoked under the following conditions:<ul><li>Violation of association bylaws or acting against the interests of the group.</li><li>Engaging in criminal activities or transporting illegal substances.</li></ul>` },
                { heading: '5. Dispute Resolution', body: `Any disputes must first be reported to the Association Committee before seeking external legal intervention.` },
                { heading: '6. Political Neutrality', body: `Members must not use Association platforms for political campaigning, especially during election periods.` },
                { heading: '7. Participation & Meetings', body: `Members are expected to attend General Body Meetings regularly and actively participate in Association activities.` },
                { heading: '8. Non-Discrimination Policy', body: `The Association ensures equal treatment of all members regardless of caste, religion, or background.` },
                { heading: '10. Legal Policy', body: `All legal matters will be subject to the jurisdiction of the courts in Hyderabad only.` }
            ]
        },
        TELUGU: {
            modalTitle: 'నిబంధనలు & షరతులు – TGTDA',
            acceptLabel: 'నేను నిబంధనలు & షరతులను చదివి అంగీకరిస్తున్నాను',
            acceptBtn: 'అంగీకరించు & మూసివేయు',
            sections: [
                { heading: '1. సభ్యత్వం & రుసుములు', body: `సంఘం సభ్యుల సహకారంతో నడుస్తుంది. నమోదు చేసుకోవడం ద్వారా, మీరు ఈ క్రింది రుసుము నిర్మాణానికి అంగీకరిస్తున్నారు:<ul><li><strong>ప్రవేశ రుసుము:</strong> చేరిన సమయంలో ఒకేసారి చెల్లించాలి.</li><li><strong>వార్షిక చందా:</strong> సభ్యత్వాన్ని కొనసాగించడానికి పునరావృత రుసుము.</li></ul>` },
                { heading: '2. పత్రాల అవసరాలు', body: `వృత్తిపరమైన ప్రమాణాలను కాపాడటానికి క్రింది పత్రాలు అవసరం:<ul><li><strong>డ్రైవర్ నమోదు:</strong> చెల్లుబాటు అయ్యే డ్రైవింగ్ లైసెన్స్ తప్పనిసరి.</li><li><strong>KYC పాటింపు:</strong> ఆధార్ కార్డ్ మరియు PAN కార్డ్ కాపీలు అవసరం.</li></ul>` },
                { heading: '3. సంక్షేమ & మద్దతు విధానం', body: `సంఘం మరణ లేదా సంక్షేమ నిధులకు నిర్బంధ చందాలను అమలు చేయదు.` },
                { heading: '4. ప్రవర్తనా నియమావళి', body: `నేర కార్యకలాపాలలో పాల్గొనడం లేదా చట్టవిరుద్ధమైన వస్తువులను రవాణా చేస్తే సభ్యత్వం రద్దు చేయబడవచ్చు.` },
                { heading: '5. వివాద పరిష్కారం', body: `వివాదాలు ముందుగా సంఘం కమిటీకి నివేదించాలి.` },
                { heading: '10. చట్టపరమైన విధానం', body: `అన్ని చట్టపరమైన విషయాలు కేవలం హైదరాబాద్‌లోని న్యాయస్థానాల అధికార పరిధికి లోబడి ఉంటాయి.` }
            ]
        },
        HINDI: {
            modalTitle: 'नियम और शर्तें – TGTDA',
            acceptLabel: 'मैंने नियम और शर्तें पढ़ी हैं और मैं उन्हें स्वीकार करता/करती हूँ',
            acceptBtn: 'स्वीकार करें & बंद करें',
            sections: [
                { heading: '1. सदस्यता और शुल्क', body: `संघ सदस्यों के योगदान पर चलता है। पंजीकरण करके, आप निम्नलिखित शुल्क संरचना का पालन करने के लिए सहमत होते हैं:<ul><li><strong>प्रवेश शुल्क:</strong> सदस्यता लेते समय एकमुश्त भुगतान।</li><li><strong>वार्षिक सदस्यता:</strong> सक्रिय सदस्यता बनाए रखने के लिए आवर्ती शुल्क।</li></ul>` },
                { heading: '2. दस्तावेज़ आवश्यकताएँ', body: `पेशेवर मानकों को बनाए रखने के लिए निम्नलिखित दस्तावेज़ आवश्यक हैं:<ul><li><strong>चालक पंजीकरण:</strong> वैध ड्राइविंग लाइसेंस अनिवार्य है।</li><li><strong>KYC अनुपालन:</strong> आधार कार्ड और PAN कार्ड की प्रतियाँ आवश्यक हैं।</li></ul>` },
                { heading: '3. कल्याण और सहायता नीति', body: `संघ मृत्यु या कल्याण निधि के लिए अनिवार्य योगदान लागू नहीं करता।` },
                { heading: '4. आचार संहिता', body: `आपराधिक गतिविधियों या अवैध पदार्थों के परिवहन पर सदस्यता रद्द की जा सकती है।` },
                { heading: '5. विवाद समाधान', body: `विवादों को बाहरी कानूनी हस्तक्षेप से पहले संघ समिति को सूचित किया जाना चाहिए।` },
                { heading: '6. राजनीतिक तटस्थता', body: `सदस्यों को चुनावी अवधि के दौरान राजनीतिक प्रचार के लिए संघ के मंचों का उपयोग नहीं करना चाहिए।` },
                { heading: '7. भागीदारी और बैठकें', body: `सदस्यों से सामान्य सभा बैठकों में नियमित रूप से उपस्थित होने की अपेक्षा की जाती है।` },
                { heading: '8. गैर-भेदभाव नीति', body: `संघ सभी सदस्यों के साथ समान व्यवहार सुनिश्चित करता है।` },
                { heading: '10. कानूनी नीति', body: `सभी कानूनी मामले केवल हैदराबाद के न्यायालयों के अधिकार क्षेत्र के अधीन होंगे।` }
            ]
        }
    };
</script>
</body>
</html>
