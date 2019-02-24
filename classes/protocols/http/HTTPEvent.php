<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPEvent
 *
 * @author christian
 */
class HTTPEvent extends \ServiceNode\events\Event {
    //put your code here
    protected  $request=null;

    public function __construct(&$obj, $method, &$param) {
        parent::__construct($obj, $method, $param);
    }

    protected function invoke($param) {
        
    }

    protected function postcondinition($param) {
        
    }

    protected function precondinition($param) {
        
    }

    public function jsonSerialize() {
        
    }

}
