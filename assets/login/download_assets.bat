@echo off
:: ═══════════════════════════════════════════════════════════════
::  TGTDA – Download all CDN assets for local hosting (Windows)
::  Double-click this file OR run from Command Prompt
::  Place this .bat file in your CodeIgniter PROJECT ROOT folder
:: ═══════════════════════════════════════════════════════════════

title TGTDA Asset Downloader
color 0A
echo.
echo  ████████╗ ██████╗ ████████╗██████╗  █████╗
echo  ╚══██╔══╝██╔════╝ ╚══██╔══╝██╔══██╗██╔══██╗
echo     ██║   ██║  ███╗   ██║   ██║  ██║███████║
echo     ██║   ██║   ██║   ██║   ██║  ██║██╔══██║
echo     ██║   ╚██████╔╝   ██║   ██████╔╝██║  ██║
echo     ╚═╝    ╚═════╝    ╚═╝   ╚═════╝ ╚═╝  ╚═╝
echo.
echo  Asset Downloader for CodeIgniter - Windows Edition
echo  ════════════════════════════════════════════════════
echo.

:: ── Create folder structure ──────────────────────────────────
echo [1/6] Creating folder structure...
if not exist "assets\vendor\css"                      mkdir "assets\vendor\css"
if not exist "assets\vendor\js"                       mkdir "assets\vendor\js"
if not exist "assets\vendor\fonts\bootstrap-icons"    mkdir "assets\vendor\fonts\bootstrap-icons"
if not exist "assets\vendor\fonts\google"             mkdir "assets\vendor\fonts\google"
echo       Done.
echo.

:: ── Bootstrap CSS ────────────────────────────────────────────
echo [2/6] Downloading Bootstrap 5.3.0 CSS...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' -OutFile 'assets\vendor\css\bootstrap.min.css' -UseBasicParsing"
echo       Done.

:: ── Bootstrap JS ─────────────────────────────────────────────
echo [3/6] Downloading Bootstrap 5.3.0 JS Bundle...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' -OutFile 'assets\vendor\js\bootstrap.bundle.min.js' -UseBasicParsing"
echo       Done.

:: ── Bootstrap Icons CSS ──────────────────────────────────────
echo [4/6] Downloading Bootstrap Icons 1.11.0 CSS...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css' -OutFile 'assets\vendor\css\bootstrap-icons.min.css' -UseBasicParsing"
echo       Downloading icon fonts...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2' -OutFile 'assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff2' -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff'  -OutFile 'assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff'  -UseBasicParsing"
echo       Done.

:: ── jQuery ───────────────────────────────────────────────────
echo [5/6] Downloading jQuery 3.7.0...
powershell -Command "Invoke-WebRequest -Uri 'https://code.jquery.com/jquery-3.7.0.min.js' -OutFile 'assets\vendor\js\jquery-3.7.0.min.js' -UseBasicParsing"
echo       Done.

:: ── Google Fonts (Baloo 2 + Nunito) ─────────────────────────
echo [6/6] Downloading Google Fonts (Baloo 2 + Nunito)...

:: Baloo 2
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd51ncANwr.woff2'    -OutFile 'assets\vendor\fonts\google\Baloo2-Regular.woff2'   -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd52FeANwr.woff2'    -OutFile 'assets\vendor\fonts\google\Baloo2-SemiBold.woff2' -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd515cAA.woff2'      -OutFile 'assets\vendor\fonts\google\Baloo2-Bold.woff2'      -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd518YAA.woff2'      -OutFile 'assets\vendor\fonts\google\Baloo2-ExtraBold.woff2' -UseBasicParsing"

:: Nunito
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDIkh.woff2' -OutFile 'assets\vendor\fonts\google\Nunito-Regular.woff2'  -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDFkh.woff2' -OutFile 'assets\vendor\fonts\google\Nunito-Medium.woff2'   -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDOsh.woff2' -OutFile 'assets\vendor\fonts\google\Nunito-SemiBold.woff2' -UseBasicParsing"
powershell -Command "Invoke-WebRequest -Uri 'https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDDsh.woff2' -OutFile 'assets\vendor\fonts\google\Nunito-Bold.woff2'     -UseBasicParsing"
echo       Done.

:: ── Fix Bootstrap Icons font path ───────────────────────────
echo.
echo  Fixing Bootstrap Icons font path...
powershell -Command "(Get-Content 'assets\vendor\css\bootstrap-icons.min.css') -replace '../fonts/bootstrap-icons', '../fonts/bootstrap-icons' | Set-Content 'assets\vendor\css\bootstrap-icons.min.css'"

:: ── Verify all files exist ───────────────────────────────────
echo.
echo  ════════════════════════════════════════════════════
echo  Verifying downloaded files...
echo  ════════════════════════════════════════════════════

set MISSING=0

call :CHECK "assets\vendor\css\bootstrap.min.css"           "Bootstrap CSS"
call :CHECK "assets\vendor\js\bootstrap.bundle.min.js"       "Bootstrap JS"
call :CHECK "assets\vendor\css\bootstrap-icons.min.css"      "Bootstrap Icons CSS"
call :CHECK "assets\vendor\fonts\bootstrap-icons\bootstrap-icons.woff2" "Icons Font woff2"
call :CHECK "assets\vendor\js\jquery-3.7.0.min.js"          "jQuery"
call :CHECK "assets\vendor\fonts\google\Baloo2-Regular.woff2" "Baloo2 Regular"
call :CHECK "assets\vendor\fonts\google\Baloo2-Bold.woff2"   "Baloo2 Bold"
call :CHECK "assets\vendor\fonts\google\Nunito-Regular.woff2" "Nunito Regular"
call :CHECK "assets\vendor\fonts\google\Nunito-Bold.woff2"   "Nunito Bold"

echo.
if %MISSING%==0 (
    color 0A
    echo  ✔  ALL FILES DOWNLOADED SUCCESSFULLY!
    echo.
    echo  Next steps:
    echo  1. Copy tgtda-fonts.css   →  assets\vendor\css\
    echo  2. Copy registration_view.php  →  application\views\registration\index.php
    echo  3. Copy Registration.php       →  application\controllers\
    echo  4. Copy Registration_model.php →  application\models\
    echo  5. Import tgtda_registration.sql into your database
    echo  6. Run your CodeIgniter app and visit /registration
) else (
    color 0C
    echo  ✘  Some files are missing. Check your internet connection and retry.
)

echo.
echo  Press any key to exit...
pause > nul
goto :EOF

:: ── Helper: check file exists ────────────────────────────────
:CHECK
if exist %1 (
    echo   [OK]  %~2
) else (
    echo   [!!]  MISSING: %~2  ^(%~1^)
    set /A MISSING+=1
)
goto :EOF
