<?php
namespace ServiceNode;

class TCPServer extends \ServiceNode\Server implements \ServiceNode\sockets\IServerInterface {
    private  $server=null;
    
    public function __construct($addr, $port,&$protocol) {
        $this->server = new \ServiceNode\Server($addr, $port, $protocol,'tcp');
    }
    
    public function __destruct() {
        unset($this->server);
    }

    public function handleEvent($event) {
        return($this->server->handleEvent($event));
    }
    

    public function getStatus() {
        return($this->server->getStatus());
    }

    public function start() {
        return($this->server->start());
    }  
    
    
    public function restart() {
        return($this->server->restart());
    } 
    

    public function stop() {
        return($this->server->stop());
    }

}
?>
