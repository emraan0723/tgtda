<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TGTDA — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Exo 2', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #031c68 0%, #1746c0 45%, #010270 75%, #08234c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated road lines at bottom */
        body::before {
            content: '';
            position: fixed;
            bottom: 38px; left: -100%;
            width: 300%; height: 4px;
            background: repeating-linear-gradient(90deg,
            transparent 0px, transparent 60px,
            rgba(232,24,90,0.55) 60px, rgba(232,24,90,0.55) 120px);
            animation: roadMove 3s linear infinite;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: 18px; left: -100%;
            width: 300%; height: 2px;
            background: repeating-linear-gradient(90deg,
            transparent 0px, transparent 80px,
            rgba(255,255,255,0.18) 80px, rgba(255,255,255,0.18) 140px);
            animation: roadMove 2.2s linear infinite;
            z-index: 0;
        }
        @keyframes roadMove {
            from { transform: translateX(0); }
            to   { transform: translateX(33.33%); }
        }

        /* Moving truck silhouette */
        .truck {
            position: fixed;
            bottom: 48px; left: -220px;
            width: 200px; height: 60px;
            opacity: 0.15;
            animation: truckDrive 12s linear infinite;
            z-index: 1;
        }
        .truck svg { width: 100%; height: 100%; fill: #e8185a; }
        @keyframes truckDrive {
            from { left: -220px; }
            to   { left: 110%; }
        }

        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        .orb-1 {
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(232,24,90,0.2) 0%, transparent 70%);
            top: -120px; left: -100px;
            animation: orbFloat 8s ease-in-out infinite alternate;
        }
        .orb-2 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(30,60,140,0.5) 0%, transparent 70%);
            bottom: -60px; right: -80px;
            animation: orbFloat 10s ease-in-out infinite alternate-reverse;
        }
        @keyframes orbFloat {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(25px,15px) scale(1.08); }
        }

        /* Card */
        .login-card {
            position: relative; z-index: 10;
            width: 100%; max-width: 420px;
            background: rgba(3, 0, 104, 0.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            padding: 2.5rem 2.25rem 2rem;
            box-shadow: 0 32px 80px rgba(0,0,0,0.55);
            animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(32px) scale(0.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* Top accent line */
        .login-card::before {
            content: '';
            position: absolute; top: 0; left: 10%; right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #e8185a, transparent);
            border-radius: 2px;
        }

        /* Logo circle */
        .logo-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(232,24,90,0.35);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            animation: logoFloat 4s ease-in-out infinite alternate;
        }
        @keyframes logoFloat {
            from { transform: translateY(0); box-shadow: 0 6px 24px rgba(232,24,90,0.25); }
            to   { transform: translateY(-6px); box-shadow: 0 14px 36px rgba(232,24,90,0.45); }
        }

        .brand-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 5px;
            line-height: 1;
        }
        .brand-sub {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.45);
            letter-spacing: 2.5px;
            text-transform: uppercase;
            line-height: 1.7;
        }

        .divider-pink {
            width: 50px; height: 2px;
            background: linear-gradient(90deg, transparent, #e8185a, transparent);
            margin: 1rem auto 1.5rem;
        }

        /* Form heading */
        .form-heading {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .form-subtext {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            margin-bottom: 1.25rem;
        }

        /* Labels */
        .field-label {
            display: block;
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            margin-bottom: 7px;
        }

        /* Inputs */
        .field-wrap { position: relative; margin-bottom: 1.1rem; }
        .field-wrap svg.fi {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.35);
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #fff;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.88rem;
            padding: 12px 13px 12px 40px;
            outline: none;
            transition: all 0.22s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.22); }
        .field-input:focus {
            border-color: #e8185a;
            background: rgba(232,24,90,0.07);
            box-shadow: 0 0 0 3px rgba(232,24,90,0.15);
        }
        .field-wrap:focus-within svg.fi { color: #e8185a; }

        /* Buttons */
        .btn-signin {
            width: 100%;
            background: linear-gradient(135deg, #e8185a 0%, #b00f3f 100%);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            border: none;
            border-radius: 10px;
            padding: 13px 20px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.22s;
            margin-top: 4px;
        }
        .btn-signin::after {
            content: '';
            position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
            transition: left 0.45s;
        }
        .btn-signin:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(232,24,90,0.45); }
        .btn-signin:hover::after { left: 100%; }
        .btn-signin:active { transform: translateY(0); }

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

        /* Alert */
        .alert-error {
            background: rgba(232,24,90,0.12);
            border: 1px solid rgba(232,24,90,0.35);
            color: #ff7aaa;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.8rem;
            margin-bottom: 1.1rem;
        }

        /* Footer */
        .card-footer-text {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .footer-dot {
            width: 4px; height: 4px;
            border-radius: 50%;
            background: #e8185a;
            opacity: 0.5;
        }
        .footer-txt {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.35);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<!-- Background decorations -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<div class="truck">
    <svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="20" width="140" height="35" rx="4"/>
        <rect x="130" y="10" width="65" height="45" rx="4"/>
        <circle cx="30" cy="56" r="8"/>
        <circle cx="90" cy="56" r="8"/>
        <circle cx="165" cy="56" r="8"/>
        <rect x="135" y="16" width="28" height="20" rx="2" fill="rgba(0,0,0,0.3)"/>
    </svg>
</div>

<!-- Login Card -->
<div class="login-card">

    <!-- Brand header -->
    <div class="text-center">
        <div class="logo-circle">
            <img src="<?php echo base_url(); ?>images/logo.jpg"
                    onerror="this.style.display='none';document.getElementById('logo-svg').style.display='block'"
                    alt="TGTDA" style="width:52px;height:52px;object-fit:contain;border-radius:50%;">
            <svg id="logo-svg" style="display:none" viewBox="0 0 48 48" width="42" height="42" xmlns="http://www.w3.org/2000/svg">
                <rect x="2" y="26" width="30" height="16" rx="3" fill="#0d1f4e"/>
                <rect x="28" y="18" width="18" height="24" rx="3" fill="#0d1f4e"/>
                <rect x="29" y="21" width="12" height="9" rx="2" fill="rgba(232,24,90,0.4)" stroke="#e8185a" stroke-width="1"/>
                <circle cx="10" cy="44" r="5" fill="#1a2a5e" stroke="#e8185a" stroke-width="1.5"/>
                <circle cx="10" cy="44" r="2.5" fill="#e8185a"/>
                <circle cx="32" cy="44" r="5" fill="#1a2a5e" stroke="#e8185a" stroke-width="1.5"/>
                <circle cx="32" cy="44" r="2.5" fill="#e8185a"/>
                <path d="M20 4 L30 18 L22 17 L22 26 L18 26 L18 17 L10 18 Z" fill="#e8185a"/>
            </svg>
        </div>
        <div class="brand-title">TGTDA</div>
        <div class="brand-sub">Telangana Goods Transport<br>And Drivers Association</div>
        <div class="divider-pink"></div>
    </div>

    <div class="form-heading">Welcome Back</div>
    <div class="form-subtext">Sign in to your TGTDA member portal</div>

    <?php
    $error_msg = @$this->session->flashdata('error_msg');
    if ($error_msg): ?>
        <div class="alert-error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="post" id="loginform" action="<?php echo base_url();?>user_login" autocomplete="off">
        <input type="hidden"
                name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">

        <!-- Username -->
        <div>
            <label class="field-label">Username</label>
            <div class="field-wrap">
                <svg class="fi" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input class="field-input" name="username" type="text"
                        required placeholder="Enter your username" autocomplete="off">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label class="field-label">Password</label>
            <div class="field-wrap">
                <svg class="fi" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input class="field-input" name="pass" type="password"
                        required placeholder="Enter your password">
            </div>
        </div>

        <button class="btn-signin" type="submit">Sign In &nbsp;→</button>

        <a href="<?php echo base_url(); ?>"
                style="display:inline-flex;align-items:center;gap:6px;margin-bottom:16px;
          font-family:'Rajdhani',sans-serif;font-size:0.78rem;font-weight:600;
          letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.45);
          text-decoration:none;transition:color 0.2s;"
                onmouseover="this.style.color='#e8185a'"
                onmouseout="this.style.color='rgba(255,255,255,0.45)'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="M12 5l-7 7 7 7"/>
            </svg>
            Back
        </a>
    </form>

    <div class="card-footer-text">
        <div class="footer-dot"></div>
        <span class="footer-txt">Telangana Goods Transport &amp; Drivers Association</span>
        <div class="footer-dot"></div>
    </div>

</div><!-- /login-card -->

<script src="<?php echo base_url(); ?>assets/libs/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(".preloader").fadeOut();
</script>
</body>
</html>