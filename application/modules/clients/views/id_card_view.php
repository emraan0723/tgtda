<style>
    body { background: #e8edf5; }
    .id-card { background:#fff; border-radius:16px; overflow:hidden; border:1px solid #cdd8ea; max-width:520px; margin:0 auto; }
    .card-head { background:#1e88e5; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; }
    .head-left { display:flex; align-items:center; gap:10px; }
    .logo-box { width:34px; height:34px; border-radius:7px; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; }
    .org-name { color:#fff; font-size:13px; font-weight:900; letter-spacing:.4px; }
    .org-sub  { color:rgba(255,255,255,.6); font-size:9px; letter-spacing:.3px; margin-top:1px; }
    .card-title { color:rgba(255,255,255,.7); font-size:9px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; text-align:right; }
    .card-type  { color:#fff; font-size:12px; font-weight:800; letter-spacing:.5px; margin-top:2px; text-align:right; }
    .card-body  { display:flex; }
    .photo-col  { background:#f0f5fc; width:130px; flex-shrink:0; display:flex; flex-direction:column; align-items:center; padding:20px 14px; border-right:1px solid #e0eaf5; }
    .photo-frame { width:82px; height:90px; border-radius:8px; overflow:hidden; background:#c8dff8; display:flex; align-items:center; justify-content:center; border:2px solid #1e88e5; margin-bottom:12px; }
    .photo-frame img { width:100%; height:100%; object-fit:cover; }
    .photo-ini  { font-size:28px; font-weight:900; color:#1e88e5; }
    /* ── QR code block ── */
    .qr-wrap { margin-top:10px; text-align:center; }
    .qr-wrap canvas, .qr-wrap img { width:72px !important; height:72px !important; border-radius:6px; border:2px solid #1e88e5; padding:3px; background:#fff; display:block; margin:0 auto; }
    .qr-label { font-size:7px; color:#8a9ab5; text-transform:uppercase; letter-spacing:.6px; font-weight:700; margin-top:4px; }
    /* ── end QR ── */
    .member-since { font-size:8px; color:#8a9ab5; text-transform:uppercase; letter-spacing:.6px; text-align:center; font-weight:600; }
    .member-since span { display:block; font-size:11px; color:#1a2a4a; font-weight:700; margin-top:2px; }
    .info-col   { flex:1; padding:18px 18px 14px; }
    .drv-name   { font-size:18px; font-weight:900; color:#1a2a4a; line-height:1.1; margin-bottom:3px; }
    .drv-regid  { font-size:11px; color:#1e88e5; font-weight:700; letter-spacing:.4px; margin-bottom:14px; }
    .fields     { display:grid; grid-template-columns:1fr 1fr; gap:9px 12px; }
    .field-full { grid-column:1/-1; }
    .fl { font-size:8px; color:#9aabbb; text-transform:uppercase; letter-spacing:.7px; font-weight:600; margin-bottom:2px; }
    .fv { font-size:11px; color:#1a2a4a; font-weight:600; line-height:1.3; }
    .validity-row { display:flex; gap:0; border-top:1px solid #edf2f8; margin-top:14px; }
    .vfield { flex:1; padding:8px 0; }
    .vfield:first-child { padding-right:12px; border-right:1px solid #edf2f8; }
    .vfield:last-child  { padding-left:12px; }
    .card-foot { background:#1a2a4a; padding:9px 18px; display:flex; align-items:center; justify-content:space-between; }
    .f-dot    { width:6px; height:6px; border-radius:50%; background:#4cdf90; display:inline-block; margin-right:5px; }
    .f-status { font-size:9px; color:#4cdf90; font-weight:700; text-transform:uppercase; letter-spacing:.6px; }
    .f-right  { font-size:8px; color:rgba(255,255,255,.35); letter-spacing:.4px; }
    .dl-btn   { width:100%; background:#1e88e5; color:#fff; border:none; border-radius:10px; padding:11px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px; margin-top:14px; }
    .dl-btn:hover { background:#1a7bd0; }
</style>

<div class="container-fluid py-4">
    <div class="text-center mb-3">
        <h5 class="fw-semibold mb-1" style="color:#1a2a4a;">Driver Identity Card</h5>
        <p class="text-muted" style="font-size:11px;">TGTDA &mdash; Telangana Goods Transport &amp; Drivers Association</p>
    </div>

    <div style="max-width:520px;margin:0 auto;">

        <div id="idcard" class="id-card">

            <!-- Header -->
            <div class="card-head">
                <div class="head-left">
                    <div class="logo-box">
                        <img src="<?php echo base_url('images/logo.jpg'); ?>" alt="logo" width="76">
                    </div>
                    <div>
                        <div class="org-name">TGTDA</div>
                        <div class="org-sub">Telangana Goods Transport &amp; Drivers Association</div>
                    </div>
                </div>
                <div>
                    <div class="card-title">Identity Card</div>
                    <div class="card-type"><?php echo htmlspecialchars($driver['tr_registration_type']); ?></div>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body">

                <!-- Photo Column -->
                <div class="photo-col">
                    <div class="photo-frame">
                        <?php if (!empty($driver['tr_selfie'])): ?>
                            <img src="<?php echo base_url('uploads/registration/' .$driver['tr_reg_key'].'/'. $driver['tr_selfie']); ?>" alt="Photo">
                        <?php else: ?>
                            <span class="photo-ini"><?php echo htmlspecialchars($initials); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- ── QR CODE (scan → secure profile page) ── -->
                    <div class="qr-wrap">
                        <div id="qrcode"></div>
                        <div class="qr-label">Scan to Verify</div>
                    </div>
                    <!-- ── end QR ── -->
                </div>

                <!-- Info Column -->
                <div class="info-col">
                    <div class="drv-name"><?php echo htmlspecialchars($driver['tr_full_name']); ?></div>
                    <div class="drv-regid">#<?php echo htmlspecialchars($reg_id); ?></div>

                    <div class="fields">
                        <div>
                            <div class="fl">Mobile</div>
                            <div class="fv"><?php echo htmlspecialchars($driver['tr_mobile']); ?></div>
                        </div>
                        <div>
                            <div class="fl">Date of Birth</div>
                            <div class="fv"><?php echo date('d M Y', strtotime($driver['tr_dob'])); ?></div>
                        </div>
                        <div class="field-full">
                            <div class="fl">Address</div>
                            <div class="fv"><?php echo htmlspecialchars($driver['tr_full_address']); ?></div>
                        </div>
                    </div>

                    <div class="validity-row">
                        <div class="vfield">
                            <div class="fl">Issue Date</div>
                            <div class="fv"><?php echo htmlspecialchars($issue_date); ?></div>
                        </div>
                        <div class="vfield">
                            <div class="fl">Valid Until</div>
                            <div class="fv" style="color:#1e88e5;font-weight:800;"><?php echo htmlspecialchars($valid_until); ?></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="card-foot">
                <div>
                    <span class="f-dot"></span>
                    <span class="f-status"><?php echo ucfirst($driver['tr_status']); ?></span>
                </div>
                <span class="f-right">https://tgtda.com/</span>
            </div>

        </div>

        <!-- Download — outside #idcard so it never prints -->
        <div id="dl-wrap">
            <button class="dl-btn" onclick="downloadCard()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Download ID Card
            </button>
        </div>

    </div>
</div>

<!-- QRCode.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Build the secure scan URL using tr_reg_key
    var scanUrl = '<?php echo base_url("verify/" . urlencode($driver["tr_reg_key"])); ?>';

    new QRCode(document.getElementById("qrcode"), {
        text        : scanUrl,
        width       : 72,
        height      : 72,
        colorDark   : "#1a2a4a",
        colorLight  : "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function downloadCard() {
        var wrap = document.getElementById('dl-wrap');
        wrap.style.display = 'none';
        html2canvas(document.getElementById('idcard'), {
            scale: 3,
            useCORS: true,
            backgroundColor: '#ffffff'
        }).then(function(canvas) {
            wrap.style.display = 'block';
            var a = document.createElement('a');
            a.download = 'TGTDA_<?php echo preg_replace('/\s+/', '_', $driver['tr_full_name']); ?>_IDCard.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        });
    }
</script>
