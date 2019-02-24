<?php

namespace ServiceNode\Process;

class ServiceStopper extends \ServiceNode\Process\MultiProcessController {
    protected $service = null;

    public function __construct(&$service) {
        parent::__construct(\uniqid(), 1, false);
        if ($service instanceof MultiProcessController) {
            $this->service=$service;
            $this->servicename=$this->service->getServiceName().'_stopper';
        }
    }

    protected function runChild() {
        $this->init();
        $i=0;
        while(!\is_null($this->service->getStatus()) && ($i<3)) {
            $this->stopService();
            \usleep(1000);
            \pcntl_signal_dispatch();
            $i++;
        }
        $this->stop();
        exit(0);
    }
    
    
    protected function stopService() {
        $pid = $this->service->getStatus();
       // if ((!\is_null($pid)) && ($pid != \getmypid())) {
        if (!\is_null($pid)) {
            \trigger_error("try to stop ".$pid." ". \PHP_EOL, \E_USER_NOTICE); 
            \posix_kill($pid, \SIGTERM);
        }
    }
    
    
     public function execute() {
        $this->startWork();
    }

}

?>
