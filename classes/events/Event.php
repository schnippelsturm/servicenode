<?php

namespace ServiceNode\events;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author christian
 */
abstract class Event implements \JsonSerializable {

    protected $parameterDefinition;
    protected $obj;
    protected $method;
    protected $param;

    public function __construct($paramDef) {
        if (Event instanceof ParameterDefinition) {
            $this->parameterDefinition = $paramDef;
        }
    }
    
    public function __invoke($param) {
        $pa=new \ServiceNode\parameter\Parameter($paramdefinition);
        $pa->getParameterDefinition();
        if ($this->parameterDefinition==$pa->getParameterDefinition()) {
             $this->invoke($param);
        }
    }
    
    protected abstract function invoke($param);
    
    protected abstract function precondinition($param);
    
    protected abstract function postcondinition($param);
}
