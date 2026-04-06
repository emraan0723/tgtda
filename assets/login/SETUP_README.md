# TGTDA Registration – Local Assets Setup
Zero CDN. All libraries hosted on your server for maximum speed.

## Folder Structure (inside CodeIgniter root)

```
your-codeigniter-project/
├── assets/
│   └── vendor/
│       ├── css/
│       │   ├── bootstrap.min.css          ← Bootstrap 5.3.0
│       │   ├── bootstrap-icons.min.css    ← Bootstrap Icons 1.11.0
│       │   └── tgtda-fonts.css            ← Local Google Fonts (provided)
│       ├── js/
│       │   ├── jquery-3.7.0.min.js        ← jQuery
│       │   └── bootstrap.bundle.min.js    ← Bootstrap JS + Popper
│       └── fonts/
│           ├── bootstrap-icons/
│           │   ├── bootstrap-icons.woff2
│           │   └── bootstrap-icons.woff
│           └── google/
│               ├── Baloo2-Regular.woff2
│               ├── Baloo2-SemiBold.woff2
│               ├── Baloo2-Bold.woff2
│               ├── Baloo2-ExtraBold.woff2
│               ├── Nunito-Regular.woff2
│               ├── Nunito-Medium.woff2
│               ├── Nunito-SemiBold.woff2
│               └── Nunito-Bold.woff2
├── application/
│   ├── controllers/
│   │   └── Registration.php               ← provided
│   ├── models/
│   │   └── Registration_model.php         ← provided
│   └── views/
│       └── registration/
│           └── index.php                  ← registration_view.php (rename)
└── uploads/
    └── registration/                      ← auto-created, chmod 755
```

## Option A – Auto-download (Linux/Mac server)

```bash
# From your CodeIgniter project root:
bash download_assets.sh
```

## Option B – Manual download (Windows / cPanel)

Download each file and place in the correct folder:

| File | Download URL | Save as |
|------|-------------|---------|
| Bootstrap CSS | https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css | assets/vendor/css/bootstrap.min.css |
| Bootstrap JS | https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js | assets/vendor/js/bootstrap.bundle.min.js |
| Bootstrap Icons CSS | https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css | assets/vendor/css/bootstrap-icons.min.css |
| Icons woff2 | https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2 | assets/vendor/fonts/bootstrap-icons/bootstrap-icons.woff2 |
| Icons woff | https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff | assets/vendor/fonts/bootstrap-icons/bootstrap-icons.woff |
| jQuery | https://code.jquery.com/jquery-3.7.0.min.js | assets/vendor/js/jquery-3.7.0.min.js |

**Google Fonts (use https://gwfh.mranftl.com for easy download):**
- Search "Baloo 2" → select weights 400,600,700,800 → Download → extract to assets/vendor/fonts/google/
- Search "Nunito" → select weights 400,500,600,700 → Download → extract to assets/vendor/fonts/google/

## Fix Bootstrap Icons font path

After downloading bootstrap-icons.min.css, open it and change:
```
../fonts/bootstrap-icons  →  ../fonts/bootstrap-icons
```
(Path is already correct if you follow the folder structure above.)

## CodeIgniter config/autoload.php

```php
$autoload['helper'] = array('url', 'form');
$autoload['libraries'] = array('session', 'upload', 'form_validation');
```

## CodeIgniter config/routes.php

```php
$route['registration'] = 'registration/index';
$route['registration/check_mobile'] = 'registration/check_mobile';
$route['registration/send_otp'] = 'registration/send_otp';
$route['registration/verify_otp'] = 'registration/verify_otp';
$route['registration/check_aadhar'] = 'registration/check_aadhar';
$route['registration/submit'] = 'registration/submit';
```

## SMS Gateway (production)

In Registration.php → send_otp(), replace the comment with your SMS API:

```php
// MSG91 example:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.msg91.com/api/v5/otp");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'template_id' => 'YOUR_TEMPLATE_ID',
    'mobile'      => '91' . $mobile,
    'authkey'     => 'YOUR_AUTH_KEY',
    'otp'         => $otp
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);
```
