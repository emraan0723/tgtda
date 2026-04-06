<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Common Helper — PHP 5.6 + 7.x compatible
 */

if (!function_exists('status_badge')) {
    function status_badge($status) {
        $map = array(
            'pending'  => 'warning',
            'active'   => 'success',
            'inactive' => 'secondary',
            'approved' => 'info',
            'rejected' => 'danger',
        );
        $key   = strtolower((string)$status);
        $color = isset($map[$key]) ? $map[$key] : 'secondary';
        return '<span class="badge bg-' . $color . ' text-uppercase">' . htmlspecialchars((string)$status) . '</span>';
    }
}

if (!function_exists('format_date')) {
    function format_date($datetime, $format = 'd M Y, h:i A') {
        if (empty($datetime)) return '&mdash;';
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('mask_aadhar')) {
    function mask_aadhar($aadhar) {
        $aadhar = (string)$aadhar;
        if (strlen($aadhar) < 4) return $aadhar;
        return str_repeat('X', strlen($aadhar) - 4) . substr($aadhar, -4);
    }
}

if (!function_exists('mask_mobile')) {
    function mask_mobile($mobile) {
        $mobile = (string)$mobile;
        if (strlen($mobile) < 4) return $mobile;
        return str_repeat('X', strlen($mobile) - 4) . substr($mobile, -4);
    }
}

if (!function_exists('thumb_path')) {
    function thumb_path($filename) {
        if (empty($filename)) return base_url('assets/img/no-doc.png');
        return base_url('uploads/registrations/' . $filename);
    }
}

if (!function_exists('reg_type_icon')) {
    function reg_type_icon($type) {
        return strtolower((string)$type) === 'driver' ? 'fa-id-card' : 'fa-truck';
    }
}

if (!function_exists('ajax_response')) {
    function ajax_response($status, $message, $data = array()) {
        header('Content-Type: application/json');
        echo json_encode(array_merge(
            array('status' => $status, 'message' => $message),
            $data
        ));
        exit;
    }
}

if (!function_exists('generate_password')) {
    function generate_password($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        $chars_len = strlen($chars);
        $pwd = '';
        for ($i = 0; $i < $length; $i++) {
            $pwd .= $chars[mt_rand(0, $chars_len - 1)];
        }
        return $pwd;
    }
}

// PHP 5.6 compatibility shim for hash_equals
if (!function_exists('hash_equals')) {
    function hash_equals($known, $user) {
        if (strlen($known) !== strlen($user)) return false;
        $diff = 0;
        for ($i = 0; $i < strlen($known); $i++) {
            $diff |= ord($known[$i]) ^ ord($user[$i]);
        }
        return $diff === 0;
    }
}
