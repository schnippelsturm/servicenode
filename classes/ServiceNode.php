<?php

class ServiceNode implements \ServiceNode\sockets\IServerInterface {

    protected $server = null;

    public function __construct(&$server) {
        if ($server instanceof ServiceNode\sockets\IServerInterface) {
          $this->server = &$server;
        }
    }

    public function getStatus() {
        return($this->server->getStatus());
    }

    public function start() {
        return($this->server->start());
    }

    public function stop() {
        return($this->server->stop());
    }

}

?>
