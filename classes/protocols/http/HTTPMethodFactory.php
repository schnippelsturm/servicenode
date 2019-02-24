<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPMethodFactory
 *
 * @author christian
 */
class HTTPMethodFactory {

    protected $getMethod;
    protected $postMethod;
    protected $headMethod;
    protected $deleteMethod;
    protected $optionsMethod;
    protected $traceMethod;
    protected $putMethod;
    protected $connectMethod;
    protected $rootdir;

    public function __construct($rootdir) {
        $this->rootdir = $rootdir;
        $this->getMethod = new HTTPGet($this->rootdir);
        $this->postMethod = new HTTPPost($this->rootdir);
        $this->traceMethod = new HTTPTrace($this->rootdir);
        $this->optionsMethod = new HTTPOptions($this->rootdir);
        $this->putMethod = new HTTPPut($this->rootdir);
        $this->connectMethod = new HTTPConnect($this->rootdir);
        $this->headMethod = new HTTPHead($this->rootdir);
    }

    public function getMethod($method) {
        $result = null;
        switch ($method) {
            case HTTP_METHOD_GET : $result = &$this->getMethod;
                break;
            case HTTP_METHOD_POST : $result = &$this->postMethod;
                break;
            case HTTP_METHOD_TRACE : $result = &$this->traceMethod;
                break;
            case HTTP_METHOD_OPTIONS: $result = &$this->optionsMethod;
                break;
            case HTTP_METHOD_HEAD : $result = &$this->headMethod;
                break;
            case HTTP_METHOD_PUT : $result = &$this->putMethod;
                break;
            case HTTP_METHOD_CONNECT: $result = &$this->connectMethod;
                break;
            case HTTP_METHOD_DELETE : $result = &$this->deleteMethod;
                break;
        }
        return($result);
    }

    public function __destruct() {
        unset($this->getMethod);
        unset($this->postMethod);
        unset($this->putMethod);
        unset($this->deleteMethod);
        unset($this->traceMethod);
        unset($this->connectMethod);
        unset($this->optionsMethod);
        unset($this->headMethod);
    }

}
