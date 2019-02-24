<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPRequest
 *
 * @author christian
 */
class HTTPResponse {

    protected $response;
    protected $header = array();
    protected $protocol = 'HTTP';
    protected $version = '1.1';
    protected $returncode = \HTTP_RETURNCODE_OK_200;

    public function __construct($response) {
        $this->response = $response;
        $this->handleResponse();
    }

    public function handleResponse() {
        $this->setReturnCode(\HTTP_RETURNCODE_OK_200);
        if ($this->response === false) {
            $this->header = array();
            $this->setReturnCode(\HTTP_RETURNCODE_NOT_FOUND_404);
        }
        if (\is_null($this->response)) {
            $this->header = array();
            $this->setReturnCode(\HTTP_RETURNCODE_INTERNAL_SERVERERROR_500);
        }
    }

    public function getHeadestr() {
        return($this->header);
    }

    public function addHeader($param, $value) {
        $this->header[$param] = $value;
    }

    public function removeHeader($param) {
        if (\key_exists($param, $this->header)) {
            unset($this->header[$param]);
        }
    }

    public function headerExists($param) {
        return(\key_exists($param, $this->header));
    }

    public function getReturnCode() {
        return($this->returncode);
    }

    public function setReturnCode($returnCode) {
        $this->returncode = $returnCode;
    }

    public function removeHeaders() {
        unset($this->header);
        $this->header = array();
    }

    public function getHeaderValues() {
        $result = array();
        foreach ($this->header as $key => $value) {
            $pos = $key . ': ' . $value;
            $result[] = $pos;
        }
        return($result);
    }

    public function getHeaderStr() {
        $result = $this->protocol . '/' . $this->version . ' ' . $this->returncode . "\r\n";
        $result.=\implode("\r\n", $this->getHeaderValues());
        return($result);
    }

    public function getResponseMessage() {
        $result = $this->protocol . '/' . $this->version . ' ' . $this->returncode . "\r\n";
        $result.=\implode("\r\n", $this->getHeaderValues());
        return($result . "\r\n\r\n" . $this->response);
    }

    public function getContent() {
        return($this->response);
    }

    public function setContent($content) {
        $this->response = $content;
        $this->handleResponse();
    }

}
