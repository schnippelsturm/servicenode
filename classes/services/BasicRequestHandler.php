<?php

namespace ServiceNode\services;


class BasicRequestHandler implements IRequestHandler {

    protected $protocol = null;

    public function __construct($requestProtocol) {
        $this->protocol = $requestProtocol;
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
