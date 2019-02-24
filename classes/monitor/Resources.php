<?php

namespace ServiceNode\monitor;

/**
 * Description of Resources
 *
 * @author christian
 */
class Resources {

    //put your code here
    protected $usage = null;
    protected $ticker = 1;

    public function __construct() {
        
    }

    protected function getUsage() {
        $this->usage = \getrusage(2);
    }

    public function getSwaps() {
        return($this->usage['ru_nswap']);
    }

    public function getPageFaults() {
        return($this->usage['ru_majflt']);
    }

    public function getUserTime() {
        return($this->usage['ru_utime.tv_usec']);
    }

//	$dat["ru_majflt"];        // number of page faults
// $dat["ru_utime.tv_sec"];  // user time used (seconds)
// $dat["ru_utime.tv_usec"]; // user time used (microseconds)
}
