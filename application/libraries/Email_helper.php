<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'PHPMailer-master/PHPMailerAutoload.php';
include_once 'PHPMailer-master/class.phpmailer.php';

class Email_helper
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function send_password_email($to_email, $to_name, $mobile, $password, $status = 'active')
    {
        $subject = 'TGTDA — Your Account Credentials';
        $body    = $this->_password_template($to_name, $mobile, $password, $status);
        return $this->_send($to_email, $to_name, $subject, $body);
    }


    public function send_status_email($to_email, $to_name, $mobile, $new_status)
    {
        $subject = 'TGTDA — Account Status Update';
        $body    = $this->_status_template($to_name, $mobile, $new_status);
        return $this->_send($to_email, $to_name, $subject, $body);
    }

    private function _send($to_email, $to_name, $subject, $body)
    {
        $this->CI->config->load('tgtda_email');
        $host      = $this->CI->config->item('tgtda_smtp_host');
        $port      = (int) $this->CI->config->item('tgtda_smtp_port');
        $user      = $this->CI->config->item('tgtda_smtp_user');
        $pass      = $this->CI->config->item('tgtda_smtp_pass');
        $from      = $this->CI->config->item('tgtda_smtp_from');
        $from_name = $this->CI->config->item('tgtda_smtp_from_name');
        $debug     = (int) $this->CI->config->item('tgtda_smtp_debug');

        $mail = new PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = $port;

            // Debug — set tgtda_smtp_debug = 3 in config to troubleshoot
            $mail->SMTPDebug   = $debug;
            $mail->Debugoutput = function($str, $level) {
                log_message('debug', '[PHPMailer] ' . $str);
            };

            // SSL fix for cPanel / self-signed certs
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                )
            );

            $mail->setFrom($from, $from_name);
            $mail->addAddress($to_email, $to_name);
            $mail->isHTML(true);
            $mail->CharSet       = 'UTF-8';
            $mail->SMTPKeepAlive = true;

            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            if (!$mail->send()) {
                log_message('error', '[Email_helper] Send failed: ' . $mail->ErrorInfo);
                return array('status' => 'error', 'message' => $mail->ErrorInfo);
            }

            return array('status' => 'success', 'message' => 'Email sent successfully');

        } catch (Exception $e) {
            log_message('error', '[Email_helper] Exception: ' . $mail->ErrorInfo);
            return array('status' => 'error', 'message' => $mail->ErrorInfo);
        }
    }

    private function _password_template($name, $mobile, $password, $status)
    {
        $status_color = ($status === 'active') ? '#10b981' : '#f59e0b';
        $status_label = strtoupper($status);

        return '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:30px 0;">
  <tr><td align="center">
    <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
      <tr>
        <td style="background:#1e88e5;padding:28px 32px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <div style="color:#fff;font-size:20px;font-weight:900;letter-spacing:.5px;">TGTDA</div>
                <div style="color:rgba(255,255,255,.7);font-size:11px;margin-top:2px;">Telangana Goods Transport &amp; Drivers Association</div>
              </td>
              <td align="right">
                <span style="background:rgba(255,255,255,.15);color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:20px;border:1px solid rgba(255,255,255,.3);">Account Credentials</span>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:32px;">
          <p style="font-size:15px;color:#1a2a4a;font-weight:700;margin:0 0 6px;">Hello, ' . htmlspecialchars($name) . '!</p>
          <p style="font-size:13px;color:#64748b;margin:0 0 24px;line-height:1.6;">Your TGTDA account has been set to <span style="color:' . $status_color . ';font-weight:700;">' . $status_label . '</span>. Below are your login credentials.</p>
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f5fc;border-radius:12px;border:1px solid #c7d6f5;margin-bottom:24px;">
            <tr>
              <td style="padding:20px 24px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e0eaf5;">
                      <span style="font-size:10px;color:#8a9ab5;text-transform:uppercase;letter-spacing:.7px;font-weight:600;">Mobile No (Username)</span><br>
                      <span style="font-size:16px;color:#1a2a4a;font-weight:700;font-family:monospace;">' . htmlspecialchars($mobile) . '</span>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;">
                      <span style="font-size:10px;color:#8a9ab5;text-transform:uppercase;letter-spacing:.7px;font-weight:600;">Password</span><br>
                      <span style="font-size:18px;color:#1e88e5;font-weight:900;font-family:monospace;letter-spacing:2px;">' . htmlspecialchars($password) . '</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border-radius:10px;border:1px solid #fde68a;margin-bottom:24px;">
            <tr>
              <td style="padding:12px 16px;font-size:12px;color:#92400e;">
                &#9888; <strong>Important:</strong> Please change your password after your first login. Do not share your credentials with anyone.
              </td>
            </tr>
          </table>
          <p style="font-size:12px;color:#64748b;margin:0 0 6px;">Account Status</p>
          <span style="background:' . $status_color . '22;color:' . $status_color . ';font-size:11px;font-weight:700;padding:5px 14px;border-radius:20px;letter-spacing:.5px;text-transform:uppercase;">&#9679; ' . $status_label . '</span>
        </td>
      </tr>
      <tr>
        <td style="background:#1a2a4a;padding:16px 32px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:11px;color:rgba(255,255,255,.5);">&copy; ' . date('Y') . ' TGTDA &mdash; Telangana Goods Transport &amp; Drivers Association</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>';
    }

    private function _status_template($name, $mobile, $new_status)
    {
        $status_color = ($new_status === 'active')   ? '#10b981'
                      : (($new_status === 'rejected') ? '#ef4444'
                      : (($new_status === 'inactive') ? '#64748b' : '#f59e0b'));

        $status_msg = array(
            'active'   => 'Your account is now active. You can log in and access all member services.',
            'inactive' => 'Your account has been temporarily deactivated. Contact your admin for assistance.',
            'pending'  => 'Your account is under review. You will be notified once approved.',
            'rejected' => 'Your registration has been rejected. Please contact your district admin.',
        );
        $msg = isset($status_msg[$new_status]) ? $status_msg[$new_status] : 'Your account status has been updated.';

        return '<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:30px 0;">
  <tr><td align="center">
    <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
      <tr>
        <td style="background:#1e88e5;padding:28px 32px;">
          <div style="color:#fff;font-size:20px;font-weight:900;">TGTDA</div>
          <div style="color:rgba(255,255,255,.7);font-size:11px;margin-top:2px;">Telangana Goods Transport &amp; Drivers Association</div>
        </td>
      </tr>
      <tr>
        <td style="padding:32px;">
          <p style="font-size:15px;color:#1a2a4a;font-weight:700;margin:0 0 6px;">Hello, ' . htmlspecialchars($name) . '!</p>
          <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0 0 20px;">' . $msg . '</p>
          <p style="font-size:12px;color:#64748b;margin:0 0 6px;">Account Status</p>
          <span style="background:' . $status_color . '22;color:' . $status_color . ';font-size:11px;font-weight:700;padding:5px 14px;border-radius:20px;letter-spacing:.5px;text-transform:uppercase;">&#9679; ' . strtoupper($new_status) . '</span>
          <p style="font-size:12px;color:#94a3b8;margin:20px 0 0;">Mobile: <strong style="color:#1a2a4a;">' . htmlspecialchars($mobile) . '</strong></p>
        </td>
      </tr>
      <tr>
        <td style="background:#1a2a4a;padding:16px 32px;">
          <span style="font-size:11px;color:rgba(255,255,255,.5);">&copy; ' . date('Y') . ' TGTDA</span>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>';
    }
}
