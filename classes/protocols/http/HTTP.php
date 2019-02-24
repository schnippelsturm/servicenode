<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 *
 * @author christian
 */

class HTTP implements HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $methodfac = null;
    protected $protocol = 'HTTP';
    protected $version = '1.1';
    protected $software = 'ServiceNode';

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->methodfac = new HTTPMethodFactory($this->document_root);
    }

    public function handleRequest($raw_request) {
        $request = new HTTPRequest($raw_request);
        $methodname = $request->getMethod();
        $method = $this->methodfac->getMethod($methodname);
        if (\is_null($method)) {
            throw new HTTPException(HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405, 405, null);
        }
        $response = $method->handleRequest($request);
        return($response);
    }
    
    
    public function setServerVARS($request) {
        $_SERVER['SERVER_PROTOCOL']= $this->protocol.'/'.$this->version;
        $_SERVER['REQUEST_METHOD']= $request->getMethod();
        $_SERVER['SERVER_SOFTWARE']=$this->software;
    }


    public function __destruct() {
        unset($this->methodfac);
    }

    /*public function readRequestStream($stream) {
        if (\is_resource($var)) {
            $this->methodfac->readRe
        }
    }*/

}
