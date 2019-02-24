<?php

namespace ServiceNode\sockets;

interface IServerInterface {

    public function getStatus();

    public function start();

    public function stop();
}

?>
