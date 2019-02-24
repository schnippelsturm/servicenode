<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPUtil
 *
 * @author Christian
 * 
 */
class HTTPUtil {

    const HTTP_RETURNCODE_CONTINUE_100 = '100 Continue';
    const HTTP_RETURNCODE_SWITCHING_PROTOCOLS_101 = '101 Switching Protocols';
    const HTTP_RETURNCODE_PROCESSING_102 = '102 Processing';
    const HTTP_RETURNCODE_OK_200 = '200 OK';
    const HTTP_RETURNCODE_CREATED_201 = '201 Created';
    const HTTP_RETURNCODE_ACCEPTED_202 = '202 Accepted';
    const HTTP_RETURNCODE_NON_AUTHORITATIVE_INFOMRMATION = 'Non-Authoritative Information 203';
    const HTTP_RETURNCODE_MULTIPLE_CHOICES_300 = '300 Multiple Choices';
    const HTTP_RETURNCODE_MOVED_PERMANENTLY_301 = '301 Moved Permanently';
    const HTTP_RETURNCODE_FOUND_302 = '302 Found';
    const HTTP_RETURNCODE_SEE_OTHER_303 = '303 See Other';
    const HTTP_RETURNCODE_NOT_MODIFIED_304 = '304 Not Modified';
    const HTTP_RETURNCODE_USE_PROXY_305 = "305 Use Proxy";
    const HTTP_RETURNCODE_SWITCH_PROXY_306 = '306 Switch Proxy';
    const HTTP_RETURNCODE_TEMPORARY_REDIRECT_307 = '307 Temporary Redirect';
    const HTTP_RETURNCODE_PERMANENT_REDIRECT_308 = '308 Permanent Redirect';
    const HTTP_RETURNCODE_BAD_REQUEST_400 = '400 Bad Request';
    const HTTP_RETURNCODE_UNAUTHORIZED_401 = '401 Unauthorized';
    const HTTP_RETURNCODE_PAYMENT_REQUIRED_402 = '402 Payment Required';
    const HTTP_RETURNCODE_FORBIDDEN_403 = '403 Forbidden';
    const HTTP_RETURNCODE_NOT_FOUND_404 = '404 Not Found';
    const HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405 = '405 Method Not Allowed';
    const HTTP_RETURNCODE_INTERNAL_SERVERERROR_500 = '500 Internal Server Error';
    const HTTP_RETURNCODE_VERSION_NOT_SUPPORTED_505 = '505 HTTP Version not supported';

    const returncodes = [100 => self::HTTP_RETURNCODE_CONTINUE_100, 101 => self::HTTP_RETURNCODE_SWITCHING_PROTOCOLS_101,
        102 => self::HTTP_RETURNCODE_PROCESSING_102, 200 => self::HTTP_RETURNCODE_OK_200,
        201 => self::HTTP_RETURNCODE_CREATED_201,
        202 => self::HTTP_RETURNCODE_ACCEPTED_202, 203 => self::HTTP_RETURNCODE_NON_AUTHORITATIVE_INFOMRMATION,
        300 => self::HTTP_RETURNCODE_MULTIPLE_CHOICES_300, 301 => self::HTTP_RETURNCODE_MOVED_PERMANENTLY_301,
        302 => self::HTTP_RETURNCODE_FOUND_302, 303 => self::HTTP_RETURNCODE_SEE_OTHER_303,
        304 => self::HTTP_RETURNCODE_NOT_MODIFIED_304,
        305 => self::HTTP_RETURNCODE_USE_PROXY_305, 306 => self::HTTP_RETURNCODE_SWITCH_PROXY_306,
        307 => self::HTTP_RETURNCODE_TEMPORARY_REDIRECT_307, 308 => self::HTTP_RETURNCODE_PERMANENT_REDIRECT_308,
        400 => self::HTTP_RETURNCODE_BAD_REQUEST_400, 401 => self::HTTP_RETURNCODE_UNAUTHORIZED_401,
        402 => self::HTTP_RETURNCODE_PAYMENT_REQUIRED_402, 403 => self::HTTP_RETURNCODE_FORBIDDEN_403,
        404 => self::HTTP_RETURNCODE_NOT_FOUND_404, 405 => self::HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405,
        500 => self::HTTP_RETURNCODE_INTERNAL_SERVERERROR_500,
        505 => self::HTTP_RETURNCODE_VERSION_NOT_SUPPORTED_505
    ];

    public static function getReturnCodeMessage($code) {
        $message = null;
        if (\key_exists($code, self::returncodes)) {
            $codes = self::returncodes;
            $message = $codes[$code];
        }
        return $message;
    }

    public static function getReturnCodes() {
        $codes = self::returncodes;
        return $codes;
    }

}
