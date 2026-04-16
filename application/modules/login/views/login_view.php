<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TGTDA — Telangana Goods Transport & Drivers Association</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Exo+2:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0d1f4e;
            --navy2:  #152860;
            --pink:   #e8185a;
            --pink2:  #ff2d6f;
            --white:  #ffffff;
            --glass:  rgba(255,255,255,0.07);
            --glass2: rgba(255,255,255,0.13);
            --border: rgba(255,255,255,0.15);
            --shadow: 0 32px 80px rgba(0,0,0,0.55);
        }

        html, body {
            height: 100%;
            font-family: 'Exo 2', sans-serif;
            overflow: hidden;
        }

        /* ── ANIMATED BACKGROUND ── */
        .bg {
            /*  position: fixed; inset: 0;
              background: linear-gradient(135deg, #060e26 0%, #0d1f4e 40%, #1a0a2e 75%, #06101f 100%);
              z-index: 0;*/
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #031c68 0%, #1746c0 40%, #010270 75%, #08234c 100%);
            z-index: 0;
        }

        /* Road / highway lines */
        .road {
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 180px;
            background: linear-gradient(180deg, transparent 0%, rgba(232,24,90,0.06) 100%);
            overflow: hidden;
        }
        .road::before {
            content: '';
            position: absolute; bottom: 40px; left: -100%;
            width: 300%; height: 4px;
            background: repeating-linear-gradient(90deg,
            transparent 0px, transparent 60px,
            rgba(232,24,90,0.5) 60px, rgba(232,24,90,0.5) 120px);
            animation: roadLines 3s linear infinite;
        }
        .road::after {
            content: '';
            position: absolute; bottom: 18px; left: -100%;
            width: 300%; height: 2px;
            background: repeating-linear-gradient(90deg,
            transparent 0px, transparent 80px,
            rgba(255,255,255,0.2) 80px, rgba(255,255,255,0.2) 140px);
            animation: roadLines 2.2s linear infinite;
        }
        @keyframes roadLines {
            from { transform: translateX(0); }
            to   { transform: translateX(33.33%); }
        }

        /* Stars */
        .stars {
            position: absolute; inset: 0;
            background-image:
                    radial-gradient(1px 1px at 15% 10%, rgba(255,255,255,0.7) 0%, transparent 100%),
                    radial-gradient(1px 1px at 72% 22%, rgba(255,255,255,0.5) 0%, transparent 100%),
                    radial-gradient(1.5px 1.5px at 41% 5%, rgba(255,255,255,0.8) 0%, transparent 100%),
                    radial-gradient(1px 1px at 89% 14%, rgba(255,255,255,0.4) 0%, transparent 100%),
                    radial-gradient(1px 1px at 58% 35%, rgba(255,255,255,0.3) 0%, transparent 100%),
                    radial-gradient(2px 2px at 28% 28%, rgba(232,24,90,0.6) 0%, transparent 100%),
                    radial-gradient(1px 1px at 63% 8%, rgba(255,255,255,0.6) 0%, transparent 100%),
                    radial-gradient(1px 1px at 92% 38%, rgba(255,255,255,0.4) 0%, transparent 100%),
                    radial-gradient(1.5px 1.5px at 7% 44%, rgba(232,24,90,0.4) 0%, transparent 100%),
                    radial-gradient(1px 1px at 50% 18%, rgba(255,255,255,0.5) 0%, transparent 100%);
            animation: twinkle 4s ease-in-out infinite alternate;
        }
        @keyframes twinkle {
            0%  { opacity: 0.7; }
            100%{ opacity: 1.0; }
        }

        /* Glowing orbs */
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); pointer-events: none;
        }
        .orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(232,24,90,0.22) 0%, transparent 70%);
            top: -120px; left: -100px;
            animation: orbFloat 8s ease-in-out infinite alternate;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(13,31,78,0.8) 0%, rgba(30,60,140,0.3) 60%, transparent 100%);
            bottom: 0; right: -80px;
            animation: orbFloat 10s ease-in-out infinite alternate-reverse;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(232,24,90,0.12) 0%, transparent 70%);
            top: 40%; right: 10%;
            animation: orbFloat 6s ease-in-out infinite alternate;
        }
        @keyframes orbFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.1); }
        }

        /* Grid overlay */
        .grid-overlay {
            position: absolute; inset: 0;
            background-image:
                    linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.5) 40%, transparent 100%);
        }

        /* Moving truck silhouette */
        .truck-silhouette {
            position: absolute;
            bottom: 48px;
            left: -200px;
            width: 200px; height: 60px;
            animation: truckDrive 12s linear infinite;
            opacity: 0.18;
        }
        .truck-silhouette svg { width: 100%; height: 100%; fill: #e8185a; }
        @keyframes truckDrive {
            from { left: -220px; }
            to   { left: 110%; }
        }

        /* ── LAYOUT ── */
        .page {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            display: flex;
            align-items: stretch;
            width: 100%;
            max-width: 960px;
            min-height: 560px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow), 0 0 0 1px var(--border);
            animation: fadeUp 0.8s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── LEFT PANEL (Brand) ── */
        .brand-panel {
            flex: 1.1;
            background: linear-gradient(160deg, #0d1f4e 0%, #091535 60%, #0a0f24 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--pink), transparent);
            animation: scanLine 3s ease-in-out infinite;
        }
        @keyframes scanLine {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        .brand-panel::after {
            content: '';
            position: absolute; bottom: -80px; right: -80px;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(232,24,90,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Hexagon pattern */
        .hex-pattern {
            position: absolute; inset: 0; opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100'%3E%3Cpath d='M28 66L0 50V16L28 0l28 16v34z' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3Cpath d='M28 100L0 84V50l28-16 28 16v34z' fill='none' stroke='%23ffffff' stroke-width='1'/%3E%3C/svg%3E");
        }

        .logo-wrap {
            position: relative; z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            animation: fadeUp 0.9s 0.1s cubic-bezier(0.16,1,0.3,1) both;
        }

        .logo-img {
            width: 180px;
            height: 180px;
            object-fit: contain;
            filter: drop-shadow(0 8px 32px rgba(232,24,90,0.4));
            animation: logoFloat 4s ease-in-out infinite alternate;
        }
        @keyframes logoFloat {
            from { transform: translateY(0); filter: drop-shadow(0 8px 32px rgba(232,24,90,0.4)); }
            to   { transform: translateY(-8px); filter: drop-shadow(0 18px 40px rgba(232,24,90,0.6)); }
        }

        .brand-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 3.2rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 6px;
            line-height: 1;
            margin-top: 8px;
        }

        .brand-tagline {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.65rem;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
            letter-spacing: 3px;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.6;
            margin-top: 6px;
        }

        .brand-divider {
            width: 60px; height: 2px;
            background: linear-gradient(90deg, transparent, var(--pink), transparent);
            margin: 16px auto;
        }

        .brand-motto {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 4px;
        }
        .motto-pill {
            background: rgba(232,24,90,0.15);
            border: 1px solid rgba(232,24,90,0.3);
            color: rgba(255,255,255,0.7);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .motto-dot {
            color: rgba(232,24,90,0.5);
            font-size: 0.5rem;
        }

        .brand-stats {
            position: relative; z-index: 1;
            display: flex;
            gap: 24px;
            margin-top: 32px;
            animation: fadeUp 0.9s 0.25s cubic-bezier(0.16,1,0.3,1) both;
        }
        .stat-item {
            text-align: center;
        }
        .stat-num {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pink);
            line-height: 1;
        }
        .stat-lbl {
            font-size: 0.55rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-top: 2px;
        }

        /* ── RIGHT PANEL (Login form) ── */
        .form-panel {
            flex: 0.9;
            background: rgba(3, 0, 104, 0.93);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 44px;
            position: relative;
            border-left: 1px solid var(--border);
        }

        .form-panel::before {
            content: '';
            position: absolute; top: 0; right: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, var(--pink), transparent);
            opacity: 0.3;
        }

        .form-heading {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1px;
            animation: fadeUp 0.9s 0.15s cubic-bezier(0.16,1,0.3,1) both;
        }

        .form-sub {
            font-size: 0.78rem;
            color: #FFFFFF;
            margin-top: 4px;
            letter-spacing: 0.5px;
            animation: fadeUp 0.9s 0.2s cubic-bezier(0.16,1,0.3,1) both;
        }

        .form-sep {
            width: 40px; height: 3px;
            background: var(--pink);
            border-radius: 2px;
            margin: 16px 0 28px;
            animation: fadeUp 0.9s 0.22s cubic-bezier(0.16,1,0.3,1) both;
        }

        .alert-danger {
            background: rgba(232,24,90,0.12);
            border: 1px solid rgba(232,24,90,0.35);
            color: #ff6b9d;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.8rem;
            margin-bottom: 20px;
        }

        .field-group {
            margin-bottom: 18px;
            animation: fadeUp 0.9s cubic-bezier(0.16,1,0.3,1) both;
        }
        .field-group:nth-child(1) { animation-delay: 0.28s; }
        .field-group:nth-child(2) { animation-delay: 0.34s; }
        .field-group:nth-child(3) { animation-delay: 0.40s; }

        .field-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgb(255, 255, 255);
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }
        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgb(255, 255, 255);
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #fff;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.88rem;
            padding: 13px 14px 13px 40px;
            outline: none;
            transition: all 0.25s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.25); }
        .field-input:focus {
            border-color: var(--pink);
            background: rgba(232,24,90,0.07);
            box-shadow: 0 0 0 3px rgba(232,24,90,0.12);
        }
        .field-input:focus + .field-icon,
        .field-wrap:focus-within .field-icon { color: var(--pink); }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--pink) 0%, #c0124a 100%);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            border: none;
            border-radius: 10px;
            padding: 14px 20px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.25s;
            margin-top: 6px;
            animation: fadeUp 0.9s 0.46s cubic-bezier(0.16,1,0.3,1) both;
        }
        .btn-login::before {
            content: '';
            position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(232,24,90,0.4); }
        .btn-login:hover::before { left: 100%; }
        .btn-login:active { transform: translateY(0); }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, var(--pink) 0%, #c0124a 100%);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
            animation: fadeUp 0.9s 0.52s cubic-bezier(0.16,1,0.3,1) both;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, var(--pink) 0%, #c0124a 100%);
            border-color: rgba(255,255,255,0.35);
            color: #fff;

        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(232,24,90,0.4); }
        .btn-register:hover::before { left: 100%; }
        .btn-register:active { transform: translateY(0); }

        .form-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            animation: fadeUp 0.9s 0.58s cubic-bezier(0.16,1,0.3,1) both;
        }
        .footer-dot {
            width: 4px; height: 4px;
            border-radius: 50%;
            background: var(--pink);
            opacity: 0.5;
        }
        .footer-text {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.91);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 700px) {
            .brand-panel { display: none; }
            .container { max-width: 420px; min-height: unset; }
            .form-panel { padding: 40px 28px; }
            html, body { overflow: auto; }
        }
    </style>
</head>
<body>

<!-- Background layers -->
<div class="bg">
    <div class="stars"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-overlay"></div>
    <div class="road"></div>
    <!-- Moving truck -->
    <div class="truck-silhouette">
        <svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="20" width="140" height="35" rx="4"/>
            <rect x="130" y="10" width="65" height="45" rx="4"/>
            <circle cx="30" cy="56" r="8"/>
            <circle cx="90" cy="56" r="8"/>
            <circle cx="165" cy="56" r="8"/>
            <rect x="135" y="16" width="28" height="20" rx="2" fill="rgba(0,0,0,0.3)"/>
        </svg>
    </div>
</div>

<div class="page">
    <div class="container">

        <!-- LEFT: Brand Panel -->
        <div class="brand-panel">
            <div class="hex-pattern"></div>
            <div class="logo-wrap">
                <img class="logo-img"
                        src="<?php echo base_url(); ?>images/logo.jpg"
                        onerror="this.style.display='none';document.getElementById('logo-fallback').style.display='flex'"
                        alt="TGTDA Logo">
                <!-- SVG fallback if image missing -->
                <div id="logo-fallback" style="display:none;width:180px;height:180px;align-items:center;justify-content:center">
                    <svg viewBox="0 0 160 160" width="160" height="160" xmlns="http://www.w3.org/2000/svg">
                        <!-- Truck body -->
                        <rect x="10" y="70" width="100" height="55" rx="6" fill="#0d1f4e"/>
                        <rect x="95" y="50" width="55" height="75" rx="6" fill="#0d1f4e"/>
                        <!-- Cab window -->
                        <rect x="100" y="57" width="38" height="28" rx="4" fill="rgba(232,24,90,0.3)" stroke="#e8185a" stroke-width="1.5"/>
                        <!-- Wheels -->
                        <circle cx="38" cy="130" r="14" fill="#1a2a5e" stroke="#e8185a" stroke-width="2.5"/>
                        <circle cx="38" cy="130" r="7" fill="#e8185a"/>
                        <circle cx="108" cy="130" r="14" fill="#1a2a5e" stroke="#e8185a" stroke-width="2.5"/>
                        <circle cx="108" cy="130" r="7" fill="#e8185a"/>
                        <!-- Arrow -->
                        <path d="M65 15 L95 55 L75 52 L75 75 L55 75 L55 52 L35 55 Z" fill="#e8185a" opacity="0.9"/>
                        <!-- Steering wheel (Telangana) -->
                        <circle cx="108" cy="130" r="6" fill="#fff" opacity="0.15"/>
                    </svg>
                </div>
                <div class="brand-name">TGTDA</div>
                <div class="brand-tagline">Telangana Goods Transport<br>And Drivers Association</div>
                <div class="brand-divider"></div>
                <div class="brand-motto">
                    <span class="motto-pill">Service</span>
                    <span class="motto-dot">●</span>
                    <span class="motto-pill">Unity</span>
                    <span class="motto-dot">●</span>
                    <span class="motto-pill">Progress</span>
                </div>
            </div>

            <div class="brand-stats">
                <div class="stat-item">
                    <div class="stat-num">1K+</div>
                    <div class="stat-lbl">Members</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">33</div>
                    <div class="stat-lbl">Districts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">2026</div>
                    <div class="stat-lbl">Founded</div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Login Form -->
        <div class="form-panel">
            <!-- ADMIN BADGE -->
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(232,24,90,0.15);
     border:1px solid rgba(232,24,90,0.35);color:#ff8ab4;border-radius:20px;
     padding:4px 12px;font-size:0.62rem;font-weight:600;letter-spacing:2px;
     text-transform:uppercase;margin-bottom:12px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Admin Login
            </div>
            <div class="form-heading">Welcome Back</div>
            <div class="form-sub">Sign in to your TGTDA member portal</div>
            <div class="form-sep"></div>

            <?php
            $error_msg = @$this->session->flashdata('error_msg');
            if ($error_msg): ?>
                <div class="alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="post" id="loginform" action="" autocomplete="off">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="field-group">
                    <label class="field-label">Username</label>
                    <div class="field-wrap">
                        <input class="field-input" name="username" type="text"
                                required placeholder="Enter your username" autocomplete="off">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="field-wrap">
                        <input class="field-input" name="pass" type="password"
                                required placeholder="Enter your password">
                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                </div>

                <div class="field-group">
                    <button class="btn-login" type="submit">
                        Sign In &nbsp;→
                    </button>
                    <a href="Registration" class="btn-register">Register as Member</a>
                </div>

                <!-- MEMBER LOGIN BUTTON -->
                <a href="<?php echo base_url(); ?>user_login" class="btn-register"
                        style="display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Member Login
                </a>
            </form>

            <div class="form-footer">
                <div class="footer-dot"></div>
                <span class="footer-text">Telangana Goods Transport & Drivers Association</span>
                <div class="footer-dot"></div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo base_url(); ?>assets/libs/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
    $(".preloader").fadeOut();
    // Animate stat numbers
    document.querySelectorAll('.stat-num').forEach(function(el) {
        var target = el.textContent.replace(/\D/g,'');
        var suffix = el.textContent.replace(/[\d]/g,'');
        if (!target) return;
        var start = 0, end = parseInt(target), dur = 1800;
        var startTime = null;
        function step(ts) {
            if (!startTime) startTime = ts;
            var p = Math.min((ts - startTime) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.floor(eased * end) + suffix;
            if (p < 1) requestAnimationFrame(step);
        }
        setTimeout(function(){ requestAnimationFrame(step); }, 600);
    });
</script>
</body>
</html>
