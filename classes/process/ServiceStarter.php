<?php

namespace ServiceNode\Process;


class ServiceStarter extends \ServiceNode\Process\MultiProcessController {
    protected $service = null;

    public function __construct(&$service) {
        parent::__construct(\uniqid(), 1, false);
        if ($service instanceof MultiProcessController) {
            $this->service = $service;
            $this->servicename=$this->service->getServiceName().'_starter';
        }
    }

    protected function runChild() {
        $this->init();
        $this->service->start();
        \usleep(1000);
        exit(0);
    }
    
    public function execute() {
        $this->startWork();
    }
    

}

?>

