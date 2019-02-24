<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\monitor;

/**
 * Description of SystemMonitor
 *
 * @author christian
 */
class SystemMonitor {
    protected $res=null;
    protected $memory=null;
    protected $snapshots=array();
    protected $max_entries=100000;
    
    public function __construct() {
        $this->memory=new \ServiceNode\monitor\Memory('MB');
        $this->res=new \ServiceNode\monitor\Resources();
    }
  
    public function getTimes() {
        $atimes = \posix_times();
        $atimes['TIME']= \microtime(true);
        return($atimes);
    }
    
    
    public function __destruct() {
        unset($this->memory);
        unset($this->res);
    }

    public function snapshot() {
        $snapshot=  \posix_times();                
        $snapshot['TIME']= \microtime(true);
        $snapshot['']=$this->res->getUserTime();
        $snapshot['PEAK_USED_MEM']= $this->memory->getUsedPeakMemory();
        $snapshot['USED_MEM']= $this->memory->getUsedMemory();
        $this->snapshots[]=$snapshot;
    }
      
    protected function DumpSnapshot(){
        
    }
    //put your code here
}
