<?php

namespace ServiceNode;

class XMLRPCRequestHandler implements \ServiceNode\services\IRequestHandler {

    protected $protocol = null;

    public function __construct($requestProtocol) {
        $this->protocol = $requestProtocol;
    }

    public function __destruct() {
        unset($this->protocol);
    }

    public function handleRequest($request) {
        $http = new protocol\xmlrpc\XMLRPC($this->document_root);
        $response = $http->handleRequest($request);
        unset($http);
        return($response);
    }

}

?>
