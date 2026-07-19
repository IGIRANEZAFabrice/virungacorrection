<?php
// Google reCAPTCHA v2 / Enterprise Credentials
if (!defined('RECAPTCHA_SITE_KEY')) {
    define('RECAPTCHA_SITE_KEY', '6LcJCDotAAAAAPwVRmfKOpAf_NhK2QSJhUEiO-Cv');
}
if (!defined('RECAPTCHA_SECRET_KEY')) {
    define('RECAPTCHA_SECRET_KEY', '6LcJCDotAAAAAOnWISjoFTd_zJv6xdTgjA5Yq9YZ');
}
if (!defined('RECAPTCHA_PROJECT_ID')) {
    define('RECAPTCHA_PROJECT_ID', 'virungacollectiv-1782639743488');
}

/**
 * Verify a reCAPTCHA response token.
 *
 * @param string $token The client-side reCAPTCHA response token.
 * @param string|null $remoteIp The client's IP address.
 * @return bool True if the token is valid, false otherwise.
 */
function verify_recaptcha($token, $remoteIp = null) {
    if (empty($token)) {
        return false;
    }

    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $verifyData = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $token
    ];
    if ($remoteIp) {
        $verifyData['remoteip'] = $remoteIp;
    }

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($verifyData),
            'timeout' => 10
        ]
    ];

    $context = stream_context_create($options);
    $verifyResult = @file_get_contents($verifyUrl, false, $context);
    if ($verifyResult === false) {
        return false;
    }

    $verifyJson = json_decode($verifyResult);
    return !empty($verifyJson) && !empty($verifyJson->success);
}

// Load SMTP credentials from .env
if (!defined('SMTP_EMAIL') || !defined('SMTP_PASS')) {
    $smtp_email = 'fabrdaa@gmail.com';
    $smtp_pass = 'mofrqznkhkthzfog';
    
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $value = trim($value, '"\'');
                if ($key === 'smtp_email') {
                    $smtp_email = $value;
                } elseif ($key === 'smtp_pass') {
                    $smtp_pass = $value;
                }
            }
        }
    }
    
    if (!defined('SMTP_EMAIL')) {
        define('SMTP_EMAIL', $smtp_email);
    }
    if (!defined('SMTP_PASS')) {
        define('SMTP_PASS', $smtp_pass);
    }
}

