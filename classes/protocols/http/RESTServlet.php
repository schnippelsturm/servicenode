<?php

namespace ServiceNode\protocol\http;

/**
 * Description of PHPServlet
 *
 * @author christian
 */
abstract class RESTServlet extends PHPServlet implements \ServiceNode\protocol\http\HTTPMethod {

    protected function setDefaultResponseHeader(&$response) {
        $content = $response->getContent();
        if ((!\is_null($content)) && ($content != false)) {
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
            $response->addHeader('Content-Type', "application/json");
            $response->addHeader('Content-Length', \strlen($content));
            $response->addHeader('Connection', 'keep-alive');
            $response->addHeader('Keep-Alive', 'timeout=120 / max=25');
        }
    }

}
