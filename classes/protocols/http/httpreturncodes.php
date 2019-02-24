<?php
namespace ServiceNode\protocol\http;

if (!defined('HTTP_RETURNCODE_CONTINUE_100')) {
    define('HTTP_RETURNCODE_CONTINUE_100', '100 Continue');
}
if (!defined('HTTP_RETURNCODE_SWITCHING_PROTOCOLS_101')) {
    define('HTTP_RETURNCODE_SWITCHING_PROTOCOLS_101', '101 Switching Protocols');
}

if (!defined('HTTP_RETURNCODE_PROCESSING_102')) {
    define('HTTP_RETURNCODE_PROCESSING_102', '102 Processing');
}

if (!defined('HTTP_RETURNCODE_OK_200')) {
    define('HTTP_RETURNCODE_OK_200', '200 OK');
}
if (!defined('HTTP_RETURNCODE_CREATED_201')) {
    define('HTTP_RETURNCODE_CREATED_201', '201 Created');
}

if (!defined('HTTP_RETURNCODE_ACCEPTED_202')) {
    define('HTTP_RETURNCODE_ACCEPTED_202', '202 Accepted');
}

if (!defined('HTTP_RETURNCODE_NON_AUTHORITATIVE_INFOMRMATION')) {
    define('HTTP_RETURNCODE_ACCEPTED_203',  'Non-Authoritative Information 203');
}



if (!defined('HTTP_RETURNCODE_OK_300')) {
    define('HTTP_RETURNCODE_CONTINUE_300', '300 Multiple Choices');
}
if (!defined('HTTP_RETURNCODE_MOVED_PERANENTLY_301')) {
    define('HTTP_RETURNCODE_MOVED_PERANENTLY_301', '301 Moved Permanently');
}

if (!defined('HTTP_RETURNCODE_FOUND_302')) {
    define('HTTP_RETURNCODE_FOUND_302', '302 Found');
}

if (!defined('HTTP_RETURNCODE_SEE_OTHER_303')) {
    define('HTTP_RETURNCODE_SEE_OTHER_303', '303 See Other');
}

if (!defined('HTTP_RETURNCODE_NOT_MODIFIED_304')) {
    define('HTTP_RETURNCODE_NOT_MODIFIED_304', '304 Not Modified');
}
if (!defined('HTTP_RETURNCODE_NOT_MODIFIED_305')) {
    define('HTTP_RETURNCODE_NOT_MODIFIED_305', '305 Use Proxy');
}




if (!defined('HTTP_RETURNCODE_BAD_REQUEST_400')) {
    define('HTTP_RETURNCODE_BAD_REQUEST_400', '400 Bad Request');
}

if (!defined('HTTP_RETURNCODE_UNAUTHORIZED_401')) {
    define('HTTP_RETURNCODE_UNAUTHORIZED_401', '401 Unauthorized');
}

if (!defined('HTTP_RETURNCODE_PAYMENT_REQUIRED_402')) {
    define('HTTP_RETURNCODE_PAYMENT_REQUIRED_402', '402 Payment Required');
}


if (!defined('HTTP_RETURNCODE_FORBIDDEN_403')) {
    define('HTTP_RETURNCODE_FORBIDDEN_403', '403 Forbidden');
}


if (!defined('HTTP_RETURNCODE_NOT_FOUND_404')) {
    define('HTTP_RETURNCODE_NOT_FOUND_404', '404 Not Found');
}

if (!defined('HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405')) {
    define('HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405', '405 Method Not Allowed');
}

if (!defined('HTTP_RETURNCODE_INTERNAL_SERVERERROR_500')) {
    define('HTTP_RETURNCODE_INTERNAL_SERVERERROR_500', '500 Internal Server Error');
}

if (!defined('HTTP_RETURNCODE_VERSION_NOT_SUPPORTED_505')) {
    define('HTTP_RETURNCODE_VERSION_NOT_SUPPORTED_505', '505 HTTP Version not supported');
}

