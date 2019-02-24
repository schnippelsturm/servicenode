<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPRequest
 *
 * @author christian  */

class HTTPRequest implements HTTPMethod {
    protected $raw_request;
    protected $method;
    protected $url;
    protected $location;
    protected $header = array();
    protected $protocol = null;
    protected $supported_methods = array(HTTP_METHOD_GET, HTTP_METHOD_TRACE, HTTP_METHOD_DELETE,
        HTTP_METHOD_PUT, HTTP_METHOD_OPTIONS, HTTP_METHOD_POST, HTTP_METHOD_HEAD);
    protected $raw_get = null;
    protected $raw_post = null;
    protected $post = array();
    protected $get = array();
    protected $path ='/';

    public function __construct($raw_request=null) {
        $this->raw_request = $raw_request;
        $this->readRequest($raw_request);
    }

    protected function extractMethod($svalue) {
        $result = array();
        $this->method = null;
        $this->path='/';
        $values = \explode(' ', $svalue);
        if (\in_array($values[0], $this->supported_methods)) {
            $this->method = $values[0];
        }
        $this->url = $values[1];
        $this->protocol = $values[2];
        $result = \parse_url($this->url);
        $this->path=$result['path'];
        $result['url']=$this->url;
        $result['protocol']=$this->protocol;
        $result['method']=$this->method;
        return($result);
    }
    
    protected function setProperties(&$result) {
        foreach($result as $key => $value) {
            if (\property_exists(__CLASS__, $key)) {
                $this->{$key}=$value;
            }
        }
    }

    protected function extractHeader($svalue) {
        $result = array();
        $values = \explode("\r\n", $svalue);
        foreach ($values as $value) {
            $header = \explode(':', $value, 2);
            $result[$header[0]] = $header[1];
        }
        return($result);
    }
       

    protected function readRequest($request) {
        $values = \explode("\r\n\r\n", $request, 2);
        $headers = array();
        $this->raw_get= array();
        if ($values[0]) {
            $headers = \explode("\r\n", $values[0], 2);
            $this->raw_get = $this->extractMethod($headers[0]);
            $this->header = $this->extractheader($headers[1]);
        }
        if (($values[1]) && (\in_array($this->method, array(HTTP_METHOD_POST, HTTP_METHOD_PUT)))) {
            \trigger_error('RAW- POST:'.\var_export($values[1],true), E_USER_NOTICE);
            $this->raw_post =$values[1];
        }  
        \mb_parse_str($this->raw_get['query'], $this->get);
        \mb_parse_str($this->raw_post, $this->post);
    }

    public function getHeader() {
        return($this->header);
    }

    public function setHeader($header) {
        $this->header = $header;
    }

    public function getMethod() {
        return($this->method);
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getUrl() {
        return($this->url);
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getProtocol() {
        return($this->protocol);
    }

    public function setProtocol($protocol) {
        $this->protocol = $protocol;
    }

    public function getRawRequest() {
        return($this->raw_request);
    }

    public function getRawPost() {
        return($this->raw_post);
    }

    public function getRawGet() {
        return($this->raw_get);
    }

    public function getPostParams() {
        return($this->post);
    }

    public function getGetParams() {
        return($this->get);
    }

    public function getPutParams() {
        return($this->post);
    }

    public function getSupportedMethods() {
        return($this->supported_methods);
    }
    
    public function getPath() {
        return($this->path);
    }
    
    public function handleRequest($httpRequest) {
       return null; 
    } 

}
