<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TGTDA – Verification Failed</title>
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #0d1f3c 0%, #1a3a6e 60%, #1e88e5 100%);
        font-family: 'Segoe UI', system-ui, sans-serif;
        display: flex; align-items: center; justify-content: center; padding: 20px;
    }
    .card {
        background: #fff; border-radius: 20px; overflow: hidden;
        max-width: 360px; width: 100%;
        box-shadow: 0 30px 80px rgba(0,0,0,.4);
        text-align: center; padding: 40px 30px;
    }
    .icon-wrap {
        width: 72px; height: 72px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .icon-invalid { background: #fef2f2; }
    .icon-inactive { background: #fefce8; }
    h2 { font-size: 20px; font-weight: 900; color: #1a2a4a; margin-bottom: 8px; }
    p  { font-size: 13px; color: #6b7a8d; line-height: 1.6; }
    .divider { height: 1px; background: #edf2f8; margin: 22px 0; }
    .footer-text { font-size: 10px; color: #b0bcc8; letter-spacing: .4px; }
</style>
</head>
<body>
<div class="card">

<?php if ($reason === 'not_found'): ?>
    <div class="icon-wrap icon-invalid">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
    </div>
    <h2>Invalid QR Code</h2>
    <p>This QR code does not match any registered TGTDA member. It may be tampered or expired.</p>

<?php elseif (in_array($reason, array('pending','onboard'))): ?>
    <div class="icon-wrap icon-inactive">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    <h2>Verification Pending</h2>
    <p>This member's registration is currently under review. Please check back once approval is complete.</p>

<?php else: ?>
    <div class="icon-wrap icon-invalid">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    <h2>Member Not Active</h2>
    <p>This member's status is <strong><?php echo htmlspecialchars(ucfirst($reason)); ?></strong>. Only active members can be verified.</p>
<?php endif; ?>

    <div class="divider"></div>
    <p class="footer-text">https://tgtda.com/</p>
</div>
</body>
</html>
