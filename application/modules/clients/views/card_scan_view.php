<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TGTDA – Member Verification</title>
<style>
  /* ── Google Font ── */
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap');

  :root {
    --blue:#1e88e5; --dark:#1a2a4a; --green:#27a56a;
    --light:#f0f5fc; --border:#cdd8ea;
  }
  * { box-sizing:border-box; margin:0; padding:0; }
  body {
    font-family:'DM Sans',sans-serif;
    background:linear-gradient(135deg,#e8f0fb 0%,#f4f8ff 60%,#dceeff 100%);
    min-height:100vh; display:flex; flex-direction:column; align-items:center;
    padding:24px 16px 48px;
  }

  /* Header stripe */
  .page-header {
    width:100%; max-width:480px; background:var(--dark);
    border-radius:14px 14px 0 0; padding:14px 20px;
    display:flex; align-items:center; gap:12px;
  }
  .page-header img { width:36px; border-radius:6px; }
  .ph-org  { font-family:'Syne',sans-serif; font-size:15px; color:#fff; font-weight:800; }
  .ph-sub  { font-size:9px; color:rgba(255,255,255,.5); letter-spacing:.4px; }
  .ph-badge{
    margin-left:auto; background:var(--green); color:#fff;
    font-size:8px; font-weight:700; letter-spacing:.8px; text-transform:uppercase;
    padding:3px 9px; border-radius:20px;
  }

  /* Card shell */
  .verify-card {
    width:100%; max-width:480px;
    background:#fff; border:1px solid var(--border);
    border-top:none; border-radius:0 0 16px 16px;
    overflow:hidden;
  }

  /* Photo hero row */
  .hero-row {
    background:linear-gradient(135deg,var(--blue) 0%,#1565c0 100%);
    padding:20px;
    display:flex; align-items:center; gap:16px;
  }
  .hero-photo {
    width:76px; height:82px; border-radius:10px;
    border:3px solid rgba(255,255,255,.5);
    object-fit:cover; flex-shrink:0;
    background:#c8dff8; display:flex; align-items:center; justify-content:center;
    overflow:hidden;
  }
  .hero-photo span { font-size:26px; font-weight:900; color:#fff; }
  .hero-name { font-family:'Syne',sans-serif; font-size:20px; color:#fff; font-weight:800; line-height:1.1; }
  .hero-reg  { font-size:11px; color:rgba(255,255,255,.7); margin-top:4px; letter-spacing:.4px; }
  .hero-type {
    display:inline-block; margin-top:7px;
    background:rgba(255,255,255,.2); color:#fff;
    font-size:9px; font-weight:700; letter-spacing:.8px; text-transform:uppercase;
    padding:3px 10px; border-radius:20px; border:1px solid rgba(255,255,255,.3);
  }

  /* Status banner */
  .status-bar {
    padding:8px 20px; font-size:11px; font-weight:700;
    text-transform:uppercase; letter-spacing:.8px;
    display:flex; align-items:center; gap:7px;
  }
  .status-bar.active   { background:#e8faf2; color:var(--green); border-bottom:1px solid #c0f0d8; }
  .status-bar.inactive { background:#fff3f3; color:#e53935; border-bottom:1px solid #ffd5d5; }
  .status-bar.pending  { background:#fff8e1; color:#f59000; border-bottom:1px solid #ffe082; }
  .sdot { width:7px; height:7px; border-radius:50%; background:currentColor; }

  /* Section */
  .info-section { padding:16px 20px 0; }
  .section-label {
    font-size:8px; font-weight:800; color:var(--blue);
    text-transform:uppercase; letter-spacing:1px;
    border-bottom:1px solid #e4edf8; padding-bottom:5px; margin-bottom:12px;
  }
  .fields { display:grid; grid-template-columns:1fr 1fr; gap:10px 16px; margin-bottom:16px; }
  .field-full { grid-column:1/-1; }
  .fl { font-size:8px; color:#9aabbb; text-transform:uppercase; letter-spacing:.7px; font-weight:600; margin-bottom:2px; }
  .fv { font-size:12px; color:var(--dark); font-weight:600; line-height:1.3; }

  /* Footer */
  .verify-footer {
    background:var(--dark); padding:11px 20px;
    display:flex; align-items:center; justify-content:space-between;
    margin-top:16px;
  }
  .vf-left { font-size:9px; color:rgba(255,255,255,.45); }
  .vf-right{ font-size:9px; color:rgba(255,255,255,.45); }

  /* Error page */
  .error-wrap {
    width:100%; max-width:400px; background:#fff;
    border-radius:16px; border:1px solid #f5c6cb;
    padding:40px 28px; text-align:center;
    box-shadow:0 4px 32px rgba(229,57,53,.08);
  }
  .err-icon { font-size:48px; margin-bottom:16px; }
  .err-title{ font-family:'Syne',sans-serif; font-size:22px; color:#c62828; font-weight:800; margin-bottom:8px; }
  .err-msg  { font-size:13px; color:#888; line-height:1.6; }
</style>
</head>
<body>

<?php if (isset($error)): ?>

  <!-- ── Unauthorized / Not found ── -->
  <div class="error-wrap">
    <div class="err-icon">🚫</div>
    <div class="err-title">Unauthorized Access</div>
    <div class="err-msg"><?php echo htmlspecialchars($error); ?></div>
    <p style="margin-top:20px;font-size:11px;color:#bbb;">tgtda.telangana.gov.in</p>
  </div>

<?php else: ?>

  <!-- ── Verified member card ── -->
  <div class="page-header">
    <img src="<?php echo base_url('images/logo.png'); ?>" alt="TGTDA">
    <div>
      <div class="ph-org">TGTDA</div>
      <div class="ph-sub">Telangana Goods Transport &amp; Drivers Association</div>
    </div>
    <span class="ph-badge">✓ Verified</span>
  </div>

  <div class="verify-card">

    <!-- Hero -->
    <div class="hero-row">
      <div class="hero-photo">
        <?php if (!empty($driver['tr_selfie'])): ?>
          <img src="<?php echo base_url('uploads/' . $driver['tr_selfie']); ?>" alt="Photo" style="width:100%;height:100%;object-fit:cover;border-radius:7px;">
        <?php else: ?>
          <span><?php echo htmlspecialchars($initials); ?></span>
        <?php endif; ?>
      </div>
      <div>
        <div class="hero-name"><?php echo htmlspecialchars($driver['tr_full_name']); ?></div>
        <div class="hero-reg">Reg #<?php echo htmlspecialchars($driver['tr_reg_ukey']); ?></div>
        <span class="hero-type"><?php echo htmlspecialchars($driver['tr_registration_type']); ?></span>
      </div>
    </div>

    <!-- Status -->
    <?php
      $st = strtolower($driver['tr_status']);
      $stClass = in_array($st, ['active','approved']) ? 'active' : ($st === 'pending' ? 'pending' : 'inactive');
    ?>
    <div class="status-bar <?php echo $stClass; ?>">
      <span class="sdot"></span>
      Member Status: <?php echo ucfirst($driver['tr_status']); ?>
    </div>

    <!-- Personal Info -->
    <div class="info-section">
      <div class="section-label">Personal Information</div>
      <div class="fields">
        <div>
          <div class="fl">Full Name</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_full_name']); ?></div>
        </div>
        <div>
          <div class="fl">Gender</div>
          <div class="fv"><?php echo htmlspecialchars(!empty($driver['tr_gender']) ? $driver['tr_gender'] : '—'); ?></div>
        </div>
        <div>
          <div class="fl">Date of Birth</div>
          <div class="fv"><?php echo !empty($driver['tr_dob']) ? date('d M Y', strtotime($driver['tr_dob'])) : '—'; ?></div>
        </div>
        <div>
          <div class="fl">Mobile</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_mobile']); ?></div>
        </div>
        <div>
          <div class="fl">Email</div>
          <div class="fv field-full" style="word-break:break-all;"><?php echo htmlspecialchars($driver['tr_email'] ?? '—'); ?></div>
        </div>
        <div>
          <div class="fl">Type</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_registration_type']); ?></div>
        </div>
        <div>
          <div class="fl">Aadhar No.</div>
          <div class="fv">XXXX-XXXX-<?php echo substr($driver['tr_aadhar_no'], -4); ?></div>
        </div>
        <div>
          <div class="fl">PAN No.</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_pan_no'] ?? '—'); ?></div>
        </div>
      </div>
    </div>

    <!-- Address -->
    <div class="info-section">
      <div class="section-label">Address Details</div>
      <div class="fields">
        <div class="field-full">
          <div class="fl">Full Address</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_full_address'] ?? '—'); ?></div>
        </div>
        <div>
          <div class="fl">State</div>
          <div class="fv"><?php echo htmlspecialchars($state_name ?? '—'); ?></div>
        </div>
        <div>
          <div class="fl">District</div>
          <div class="fv"><?php echo htmlspecialchars($district_name ?? '—'); ?></div>
        </div>
        <div>
          <div class="fl">Mandal</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_mandal'] ?? '—'); ?></div>
        </div>
        <div>
          <div class="fl">Pincode</div>
          <div class="fv"><?php echo htmlspecialchars($driver['tr_pincode'] ?? '—'); ?></div>
        </div>
      </div>
    </div>

    <!-- Validity -->
    <div class="info-section">
      <div class="section-label">Card Validity</div>
      <div class="fields">
        <div>
          <div class="fl">Registration No.</div>
          <div class="fv" style="color:var(--blue);font-weight:800;"><?php echo htmlspecialchars($driver['tr_reg_ukey']); ?></div>
        </div>
        <div>
          <div class="fl">Registered On</div>
          <div class="fv"><?php echo htmlspecialchars($issue_date); ?></div>
        </div>
        <div>
          <div class="fl">Valid Until</div>
          <div class="fv" style="color:var(--blue);font-weight:800;"><?php echo htmlspecialchars($valid_until); ?></div>
        </div>
        <div>
          <div class="fl">Scan Verified</div>
          <div class="fv"><?php echo date('d M Y, h:i A'); ?></div>
        </div>
      </div>
    </div>

    <div class="verify-footer">
      <span class="vf-left">QR Scan Verification &nbsp;|&nbsp; TGTDA</span>
      <span class="vf-right">tgtda.telangana.gov.in</span>
    </div>

  </div>

<?php endif; ?>

</body>
</html>
