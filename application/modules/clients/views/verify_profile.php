<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TGTDA – Member Verification</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d1f3c 0%, #1a3a6e 60%, #1e88e5 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0,0,0,.4);
        }

        /* ── Header ── */
        .card-header {
            background: linear-gradient(135deg, #1e88e5, #1a2a4a);
            padding: 22px 22px 18px;
            text-align: center;
        }
        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 30px;
            padding: 4px 12px 4px 8px;
            margin-bottom: 14px;
        }
        .badge-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #4cdf90;
            box-shadow: 0 0 6px #4cdf90;
            flex-shrink: 0;
        }
        .badge-text { color: #fff; font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }

        .org-logo-row { display: flex; align-items: center; justify-content: center; gap: 10px; }
        .org-logo-box { width: 38px; height: 38px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .org-logo-box img { width: 34px; }
        .org-name-col .name { color: #fff; font-size: 15px; font-weight: 900; letter-spacing: .5px; }
        .org-name-col .sub  { color: rgba(255,255,255,.6); font-size: 9px; margin-top: 2px; }

        /* ── Photo ── */
        .photo-section {
            background: #f0f5fc;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 22px 22px 16px;
            border-bottom: 1px solid #e0eaf5;
        }
        .photo-ring {
            width: 100px; height: 100px;
            border-radius: 50%;
            border: 3px solid #1e88e5;
            padding: 3px;
            background: #fff;
            margin-bottom: 14px;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .photo-ring img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .photo-initials { font-size: 36px; font-weight: 900; color: #1e88e5; }
        .member-name  { font-size: 20px; font-weight: 900; color: #1a2a4a; text-align: center; }
        .member-regid { font-size: 12px; color: #1e88e5; font-weight: 700; letter-spacing: .5px; margin-top: 3px; }
        .member-type  {
            display: inline-block;
            background: #e8f1fd;
            color: #1e88e5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 3px 12px;
            border-radius: 20px;
            margin-top: 8px;
        }

        /* ── Details grid ── */
        .details { padding: 20px 22px 14px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 16px; }
        .detail-full { grid-column: 1 / -1; }
        .dl { font-size: 9px; color: #9aabbb; text-transform: uppercase; letter-spacing: .7px; font-weight: 700; margin-bottom: 3px; }
        .dv { font-size: 13px; color: #1a2a4a; font-weight: 600; line-height: 1.4; }

        /* ── Validity strip ── */
        .validity-strip {
            display: flex;
            background: #f8fafd;
            border-top: 1px solid #edf2f8;
            border-bottom: 1px solid #edf2f8;
            margin: 6px 0;
        }
        .vs-item { flex: 1; padding: 12px 22px; }
        .vs-item:first-child { border-right: 1px solid #edf2f8; }

        /* ── Footer ── */
        .card-footer {
            background: #1a2a4a;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(76,223,144,.15);
            border: 1px solid rgba(76,223,144,.3);
            border-radius: 20px;
            padding: 4px 12px;
        }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; background: #4cdf90; }
        .status-text { color: #4cdf90; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .6px; }
        .footer-url { color: rgba(255,255,255,.3); font-size: 9px; letter-spacing: .4px; }

        /* ── Verified banner ── */
        .verified-banner {
            background: linear-gradient(90deg, #0f9e56, #17c068);
            color: #fff;
            text-align: center;
            padding: 9px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }
        .verified-banner svg { flex-shrink: 0; }
    </style>
</head>
<body>

<div class="card">

    <!-- Verified banner at top -->
    <div class="verified-banner">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        TGTDA Verified Member
    </div>

    <!-- Header -->
    <div class="card-header">
        <div class="header-badge">
            <span class="badge-dot"></span>
            <span class="badge-text">Official Identity Verification</span>
        </div>
        <div class="org-logo-row">
            <div class="org-logo-box">
                <img src="<?php echo base_url('images/logo.jpg'); ?>" alt="TGTDA Logo">
            </div>
            <div class="org-name-col">
                <div class="name">TGTDA</div>
                <div class="sub">Telangana Goods Transport &amp; Drivers Assoc.</div>
            </div>
        </div>
    </div>

    <!-- Photo -->
    <div class="photo-section">
        <div class="photo-ring">
            <?php if (!empty($selfie)): ?>

                <img src="<?php echo base_url('uploads/registration/' .$fkey.'/'. htmlspecialchars($selfie)); ?>" alt="Member Photo">
            <?php else: ?>
                <span class="photo-initials"><?php echo htmlspecialchars($initials); ?></span>
            <?php endif; ?>
        </div>
        <div class="member-name"><?php echo htmlspecialchars($name); ?></div>
        <div class="member-regid">#<?php echo htmlspecialchars($reg_id); ?></div>
        <span class="member-type"><?php echo htmlspecialchars($reg_type); ?></span>
    </div>

    <!-- Details -->
    <div class="details">
        <div class="detail-grid">
            <div>
                <div class="dl">Mobile</div>
                <div class="dv"><?php echo htmlspecialchars($mobile); ?></div>
            </div>
            <div>
                <div class="dl">Date of Birth</div>
                <div class="dv"><?php echo htmlspecialchars($dob); ?></div>
            </div>
            <div class="detail-full">
                <div class="dl">Address</div>
                <div class="dv"><?php echo htmlspecialchars($address); ?></div>
            </div>
        </div>
    </div>

    <!-- Validity -->
    <div class="validity-strip">
        <div class="vs-item">
            <div class="dl">Issue Date</div>
            <div class="dv"><?php echo htmlspecialchars($issue_date); ?></div>
        </div>
        <div class="vs-item">
            <div class="dl">Valid Until</div>
            <div class="dv" style="color:#1e88e5;font-weight:800;"><?php echo htmlspecialchars($valid_until); ?></div>
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer">
        <div class="status-pill">
            <span class="status-dot"></span>
            <span class="status-text"><?php echo htmlspecialchars(ucfirst($status)); ?></span>
        </div>
        <span class="footer-url">https://tgtda.com/</span>
    </div>

</div>

</body>
</html>
