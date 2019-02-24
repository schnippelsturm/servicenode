<?php

namespace ServiceNode\Process;

/**
 * Description of MultiProcessControler
 *
 * @author christian
 */
abstract class AbstractProcessController {

    abstract protected function setSignalHandler($signals, $signalfunc); 
    abstract protected function init();
    abstract protected function freeResources();
    abstract protected function waitForChildren($waitforexit = false); 
    abstract protected function runParent($pid); 
    abstract protected function runChild(); 
    abstract protected function dofork();
    abstract protected function startWork();
    abstract public function halt(); 
    abstract public function handleSignalCLD($signo); 
    abstract public function handleSignals($signo);
    abstract public function getPidFileName(); 
    abstract protected function setPIDFile(); 
    abstract protected function releasePIDFile(); 
    abstract public function getStatus();
 //   abstract protected function getSysemInfos(); 
    abstract public function stop(); 

}

?>
