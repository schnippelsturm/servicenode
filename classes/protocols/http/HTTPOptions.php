<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 *
 * @author christian
 * 
 */


class HTTPOptions implements HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $httpStore = null;

    public function __construct($rootDir) {
        $this->document_root = getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->httpStore = new \ServiceNode\protocol\http\HTTPStore($this->document_root);
    }

    public function __destruct() {
        unset($this->httpStore);
    }

    protected function getDocPath($url) {
        return($this->httpStore->getDocPath($url));
    }

    public function handleRequest($httpRequest) {
        try {
            $methods=$httpRequest->getSupportedMethods();           
            $content = "";
            $response = new HTTPResponse($content);
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Server', 'Servicenode Webserver');
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
             $response->addHeader('Allow', implode(', ',$methods));
            $response->addHeader('Content-Length', \strlen($content));
            $response->addHeader('Connection', 'close');
        } catch (HTTPException $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getMessage());
        } catch (\Exception $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getMessage());
        }
        return($response->getResponseMessage());
    }

}
