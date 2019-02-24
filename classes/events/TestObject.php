<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\events;

/**
 * Description of TestObject
 *
 * @author christian
 */
class TestObject {
    protected $value=null;
   
    public function __construct($value) {
        $this->value=$value;
    }
    
    public function __destruct() { 
        unset($this->value);
    }
    //put your code here
}
