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
