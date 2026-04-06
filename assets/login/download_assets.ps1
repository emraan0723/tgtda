# ═══════════════════════════════════════════════════════════════
#  TGTDA – Download all CDN assets for local hosting
#  PowerShell version (more reliable than .bat on newer Windows)
#
#  HOW TO RUN:
#  1. Place this file in your CodeIgniter PROJECT ROOT
#  2. Right-click → "Run with PowerShell"
#     OR open PowerShell and type:
#     Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
#     .\download_assets.ps1
# ═══════════════════════════════════════════════════════════════

$Host.UI.RawUI.WindowTitle = "TGTDA Asset Downloader"

Write-Host ""
Write-Host "  TGTDA Asset Downloader - Windows PowerShell Edition" -ForegroundColor Green
Write-Host "  ══════════════════════════════════════════════════════" -ForegroundColor DarkGreen
Write-Host ""

# ── Folders ──────────────────────────────────────────────────
$folders = @(
    "assets\vendor\css",
    "assets\vendor\js",
    "assets\vendor\fonts\bootstrap-icons",
    "assets\vendor\fonts\google"
)
foreach ($f in $folders) {
    if (-not (Test-Path $f)) { New-Item -ItemType Directory -Path $f -Force | Out-Null }
}
Write-Host "  [1/6] Folder structure created." -ForegroundColor Cyan

# ── Download helper ───────────────────────────────────────────
function Get-Asset($url, $dest, $label) {
    Write-Host "        Downloading $label..." -ForegroundColor Gray
    try {
        Invoke-WebRequest -Uri $url -OutFile $dest -UseBasicParsing -ErrorAction Stop
        Write-Host "        ✔ $label" -ForegroundColor Green
    } catch {
        Write-Host "        ✘ FAILED: $label - $_" -ForegroundColor Red
    }
}

# ── Bootstrap CSS ─────────────────────────────────────────────
Write-Host ""
Write-Host "  [2/6] Bootstrap 5.3.0..." -ForegroundColor Cyan
Get-Asset "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" `
          "assets\vendor\css\bootstrap.min.css" "Bootstrap CSS"

# ── Bootstrap JS ──────────────────────────────────────────────
Write-Host ""
Write-Host "  [3/6] Bootstrap JS Bundle..." -ForegroundColor Cyan
Get-Asset "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" `
          "assets\vendor\js\bootstrap.bundle.min.js" "Bootstrap JS Bundle"

# ── Bootstrap Icons ───────────────────────────────────────────
Write-Host ""
Write-Host "  [4/6] Bootstrap Icons 1.11.0..." -ForegroundColor Cyan
Get-Asset "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" `
          "assets\vendor\css\bootstrap-icons.min.css" "Bootstrap Icons CSS"
Get-Asset "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2" `
          "assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff2" "Icons Font (.woff2)"
Get-Asset "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff" `
          "assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff" "Icons Font (.woff)"

# Fix font path in CSS
$iconsCSS = "assets\vendor\css\bootstrap-icons.min.css"
if (Test-Path $iconsCSS) {
    (Get-Content $iconsCSS) -replace '\.\.\/fonts\/', '../fonts/bootstrap-icons/' |
    Set-Content $iconsCSS
    Write-Host "        ✔ Font path fixed in bootstrap-icons.min.css" -ForegroundColor Green
}

# ── jQuery ────────────────────────────────────────────────────
Write-Host ""
Write-Host "  [5/6] jQuery 3.7.0..." -ForegroundColor Cyan
Get-Asset "https://code.jquery.com/jquery-3.7.0.min.js" `
          "assets\vendor\js\jquery-3.7.0.min.js" "jQuery"

# ── Google Fonts ──────────────────────────────────────────────
Write-Host ""
Write-Host "  [6/6] Google Fonts (Baloo 2 + Nunito)..." -ForegroundColor Cyan

$fonts = @(
    @{ url="https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd51ncANwr.woff2";    dest="assets\vendor\fonts\google\Baloo2-Regular.woff2";   label="Baloo2 Regular" },
    @{ url="https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd52FeANwr.woff2";    dest="assets\vendor\fonts\google\Baloo2-SemiBold.woff2";  label="Baloo2 SemiBold" },
    @{ url="https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd515cAA.woff2";      dest="assets\vendor\fonts\google\Baloo2-Bold.woff2";      label="Baloo2 Bold" },
    @{ url="https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd518YAA.woff2";      dest="assets\vendor\fonts\google\Baloo2-ExtraBold.woff2"; label="Baloo2 ExtraBold" },
    @{ url="https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDIkh.woff2"; dest="assets\vendor\fonts\google\Nunito-Regular.woff2";  label="Nunito Regular" },
    @{ url="https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDFkh.woff2"; dest="assets\vendor\fonts\google\Nunito-Medium.woff2";   label="Nunito Medium" },
    @{ url="https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDOsh.woff2"; dest="assets\vendor\fonts\google\Nunito-SemiBold.woff2"; label="Nunito SemiBold" },
    @{ url="https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDDsh.woff2"; dest="assets\vendor\fonts\google\Nunito-Bold.woff2";     label="Nunito Bold" }
)

foreach ($font in $fonts) {
    Get-Asset $font.url $font.dest $font.label
}

# ── Verify ────────────────────────────────────────────────────
Write-Host ""
Write-Host "  ══════════════════════════════════════════════════════" -ForegroundColor DarkGreen
Write-Host "  Verification" -ForegroundColor Cyan
Write-Host "  ══════════════════════════════════════════════════════" -ForegroundColor DarkGreen

$checks = @(
    "assets\vendor\css\bootstrap.min.css",
    "assets\vendor\js\bootstrap.bundle.min.js",
    "assets\vendor\css\bootstrap-icons.min.css",
    "assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff2",
    "assets\vendor\js\jquery-3.7.0.min.js",
    "assets\vendor\fonts\google\Baloo2-Regular.woff2",
    "assets\vendor\fonts\google\Baloo2-Bold.woff2",
    "assets\vendor\fonts\google\Nunito-Regular.woff2",
    "assets\vendor\fonts\google\Nunito-Bold.woff2"
)

$missing = 0
foreach ($f in $checks) {
    if (Test-Path $f) {
        $size = [math]::Round((Get-Item $f).Length / 1KB, 1)
        Write-Host "  ✔  $f ($size KB)" -ForegroundColor Green
    } else {
        Write-Host "  ✘  MISSING: $f" -ForegroundColor Red
        $missing++
    }
}

Write-Host ""
if ($missing -eq 0) {
    Write-Host "  ══════════════════════════════════════════════════════" -ForegroundColor DarkGreen
    Write-Host "  ALL ASSETS DOWNLOADED SUCCESSFULLY!" -ForegroundColor Green
    Write-Host ""
    Write-Host "  Next steps:" -ForegroundColor Yellow
    Write-Host "  1. Copy tgtda-fonts.css        → assets\vendor\css\"
    Write-Host "  2. Copy registration_view.php  → application\views\registration\index.php"
    Write-Host "  3. Copy Registration.php        → application\controllers\"
    Write-Host "  4. Copy Registration_model.php  → application\models\"
    Write-Host "  5. Import tgtda_registration.sql into MySQL"
    Write-Host "  6. Visit http://localhost/your-project/registration"
    Write-Host "  ══════════════════════════════════════════════════════" -ForegroundColor DarkGreen
} else {
    Write-Host "  $missing file(s) missing. Check internet and re-run." -ForegroundColor Red
}

Write-Host ""
Write-Host "  Press Enter to exit..."
Read-Host
