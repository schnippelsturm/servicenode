<?php
namespace ServiceNode;


class LocalServer extends Server implements \ServiceNode\sockets\IServerInterface {

    protected $server = null;
    protected $identifier='';
    protected $socketfile='';

    public function __construct($identifier, &$requesthandler) {
        $this->identifier=$this->normalize($identifier);
        $this->socketfile = \sys_get_temp_dir().\DIRECTORY_SEPARATOR.$this->identifier."_servicenode.sock";
        $this->server = new \ServiceNode\Server($this->socketfile, 0, $requesthandler,'unix');
}
    
    private function normalize($str) {
       return(\md5($str)); 
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

    public function stop() {
        return($this->server->stop());
    }

}
?>
