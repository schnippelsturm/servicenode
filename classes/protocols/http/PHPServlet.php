<?php

namespace ServiceNode\protocol\http;

/**
 * Description of PHPServlet
 *
 * @author christian
 */
abstract class PHPServlet implements \ServiceNode\protocol\http\HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $methodfac = null;
    protected $protocol = 'HTTP';
    protected $http = null;
    protected $registered_routes = array();

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->http = new HTTP($this->document_root);
        $this->methodfac = new HTTPMethodFactory($this->document_root);
    }
    
    abstract protected function onGet($request); 
    abstract protected function onPost($request);
    abstract protected function onPut($request);
    abstract protected function onDelete($request); 
    abstract protected function onInfo($request);
    abstract protected function onConnect($request);
    abstract protected function onHead($request);
    

    final protected function onTrace($request) {
        return($this->http->handleRequest($request->getRawRequest()));      
    }


    protected function onOptions($request) {
        return($this->http->handleRequest($request->getRawRequest()));
    }


    protected function getEnv() {
        return(array());
    }

    protected function setEnv(&$env) {
        $env = array();
    }

    public function callMethod($method,$request) {
        $response = null;
        switch ($method) {
            case HTTP_METHOD_GET :  $response = $this->onGet($request);  break;
            case HTTP_METHOD_POST : $response = $this->onPost($request); break;
            case HTTP_METHOD_TRACE :$response = $this->onTrace($request);break;
            case HTTP_METHOD_OPTIONS: $response = $this->onOptions($request);
                break;
            case HTTP_METHOD_HEAD : $response = $this->onInfo($request);break;
            case HTTP_METHOD_PUT : $response = $this->onPut($request);break;
            case HTTP_METHOD_CONNECT: $response = $this->onTrace($request);
                break;
            case HTTP_METHOD_DELETE : $response = $this->onDelete($request);
                break;
            default : throw new HTTPException(HTTPUtil::getReturnCodeMessage(405), 405, null);                               
        }       
        return($response);
    }

    public function handleRequest($raw_request) {
        $request = new HTTPRequest($raw_request);
        $methodname = $request->getMethod(); 
        $response = $this->callMethod($methodname, $request);
        return($response);
    }

   
}
