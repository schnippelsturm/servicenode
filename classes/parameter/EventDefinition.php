<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\parameter;

/**
 * Description of Parameter
 *
 * @author christian
 */
class EventDefinitionSet implements \JsonSerializable {

    //put your code here
    protected $name = '';
    protected $paramdefinitions = array();
    protected $eventhandler = null;
    protected $inputParameterDefinitionSet = null;
    protected $outputParameterDefinitionSet = null;
    protected $description = '';

    public function __construct($name, &$inputParameterDefinitionSet, &$outputParameterdefinitionSet, &$eventhandler) {
        $this->name = $name;
        $this->eventhandler = &$eventhandler;
        $this->inputParameterDefinitionSet = &$inputParameterDefinitionSet;
        $this->outputParameterDefinitionSet = &$outputParameterdefinitionSet;
    }

    public function jsonSerialize() {
        $values = array();
        $values['name'] = $this->name;
        $values['inputParameterDefinitionSet'] = $this->inputParameterDefinitionSet;
        $values['outputParameterDefinitionSet'] = $this->outputParameterDefinitionSet;
        $values['eventhandler'] = $this->eventhandler;
        return($values);
    }

}
