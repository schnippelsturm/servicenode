<?php

namespace ServiceNode\services;

class HTTPRequestHandler  implements IRequestHandler {

    protected $protocol = null;

    public function __construct($requestProtocol) {
        if ($requestProtocol instanceof \ServiceNode\protocol\http\HTTPMethod) {
            $this->protocol = $requestProtocol;
        } else {
            $this->protocol = new \ServiceNode\protocol\http\HTTP(\getcwd());
        }
    }

    public function __destruct() {
        unset($this->protocol);
    }

    public function handleRequest($request) {
        $response = $this->protocol->handleRequest($request);
        return($response);
    }

}

?>
