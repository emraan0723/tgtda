<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



#PAYPAL CONFIGRATION
//TEST


#RANGA SIR TEST LINK
/*define("PAYPAL_CLIENTID", "AUEl3eTpbhwL7-Ex9_yL2FVyOIpVMeQZAGvH2im-txjcgttSJzFgrSa9GlVIzWxBRqleGHL2b9clz8ek");
define("PAYPAL_SECRET", "EJwZnZIq_pftGQ4GooLSVuWRX6FKw9Iuq5eW0jerm9CXhmPFQJ1Alo_gE2nLcR7WhJ6bsQXc99EBEJaX");
define("PAYPAL_OAUTH_ACCESS_TOKEN_URL", "https://api.sandbox.paypal.com/v1/oauth2/token");
define("PAYPAL_ORDERS_URL", "https://api.sandbox.paypal.com/v2/checkout/orders/");
define("PAYPAL_CAPTURE_URL", "https://api.sandbox.paypal.com/v2/payments/captures/");
define("PAYPAL_BASE_URL", "https://www.paypal.com/sdk/js?client-id=");
define('CURRENCY', 'USD');*/



#TESTING

define("PAYPAL_CLIENTID", "AWC8jH59pA2iLrW6jy5R7wf-LC03hQEzEVAvOKTOB1sAzbnfcU98yJrqVQpn7lQfbixwC2ZY9pk-UU78");
define("PAYPAL_SECRET", "EH6r1U9Qc-ayx8jFXiuRRjKwSHc-WO9sB_zQH576-v77efwreGhqs9jW7F97yQTeJh88qIh-3iDFp_uV");
define("PAYPAL_OAUTH_ACCESS_TOKEN_URL", "https://api.sandbox.paypal.com/v1/oauth2/token");
define("PAYPAL_ORDERS_URL", "https://api.sandbox.paypal.com/v1/checkout/orders/");
define("PAYPAL_CAPTURE_URL", "https://api.sandbox.paypal.com/v1/payments/captures/");
define("PAYPAL_BASE_URL", "https://www.paypal.com/sdk/js?client-id=");
define('CURRENCY', 'USD');



//LIVE
/*define("PAYPAL_CLIENTID", "AftWAQx-M8RfZCTo3MrzsteYm9krowSavHgjQw0MXedvfbECYiEI9XVEbjlBYPiEtX5E6v6pg7X-aAFb");
define("PAYPAL_SECRET", "EOhzcW53whNa7F5t5tsI9NpgPNh4hRZ_TvdXp-AxyUOxVtshJK6Ik-bwU5Adaw-2eZMPSyolEg50dm7C");

define("PAYPAL_OAUTH_ACCESS_TOKEN_URL", "https://api.paypal.com/v1/oauth2/token");
define("PAYPAL_ORDERS_URL", "https://api.paypal.com/v1/checkout/orders/");
define("PAYPAL_CAPTURE_URL", "https://api.paypal.com/v1/payments/captures/");
define("PAYPAL_BASE_URL", "https://www.paypal.com/sdk/js?client-id=");
define('CURRENCY', 'USD');*/

#PHP7 MPDF
define('MPDF','vendor/autoload.php');
define('MPDF_TEMP_PATH','vendor/mpdf/mpdf/src');


defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
defined('RAZOR_KEY_ID')        OR define('RAZOR_KEY_ID', 'rzp_test_YRcjz3U9NNwzeK'); //Please change this value with live key
defined('RAZOR_KEY_SECRET')        OR define('RAZOR_KEY_SECRET', 'lKD7yhbEPMSxQgZd2CAVADow'); //Please change this value with live key secret