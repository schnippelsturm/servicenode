<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\parameter;


/**
 * Description of Paramter
 *
 * @author christian
 */
class CommandDefinition implements \JsonSerializable {

    //put your code here
    protected $name = '';
    protected $paramdefinitions = array();
    protected $eventlistener = null;
    protected $method;
    protected $description = '';

    public function __construct($name, $eventlister, $method, $desc = '') {
        $this->name = $name;
        $this->eventlistener = $eventlister;
        $this->method = $method;
        $this->description = $desc;
    }

    protected function addParameterDefinition($key, $paramdefinition) {
        if ($paramdefinition instanceof ParameterDefinition) {
            $this->paramdefinitions[$key] = $paramdefinition;
        }
    }

    protected function removeParameterDefinition($key) {
        if (\key_exists($key, $this->paramdefinitions)) {
            unset($this->paramdefinitions[$key]);
        }
    }

    public function getParameterOption() {
        \reset($this->paramdefinitions);
        $options = array();
        foreach ($this->paramdefinitions as $key => $def) {
            $value = (string) $key . ':';
            $def = new ParameterDefinition($name, $type);
            if ($def->isRequired()) {
                $value.=':';
            }
            $options[] = $value;
        }
        return($options);
    }

    public function getAllParameterDef() {
        
    }

    public function getCommandline() {
        $cmd = ' -cmd ' . $this->name . ' ';
        reset($this->paramdefinitions);
        foreach ($this->paramdefinitions as $key => $def) {
            $value = '-' . $key . ' <' . $key . '>';
            if ($def->isRequired() == false) {
                $value = ' [-' . $key . ' <' . $key . '>]';
            }
            $cmd.=' ' . $value;
        }
        return($cmd);
    }

    public function getDescription() {
        $desc = array();
        $desc[] = $this->getCommandline();
        $desc[] = $this->name . ": \r\n" . $this->description . " \r\n\r\n";
        \reset($this->paramdefinitions);
        foreach ($this->paramdefinitions as $key => $def) {
            $len = $def->getCharacterlength();
            $value = '-' . $key . ' <' . $key . "> \r\n";
            $value.= ' defines ' . $def->getName() . ' of ' . $def->getType();
            if (!\is_null($len)) {
                $value.='(' . $len . ')';
            }
            if (!\is_null($def->getDefaultValue())) {
                $value.=' default value=' . $def->getDefaultValue();
            }
            if ($def->isRequired() == true) {
                $value.=' required ';
            }
            $desc[].=' ' . $value;
        }
        return(\implode(" \r\n\r\n", $desc));
    }

    public function jsonSerialize() {
        $values = array();
        $values['name'] = $this->name;
        $values['method'] = $this->method;
        $values['eventhandler'] = $this->eventlistener;
        $values['parameterDefinitions'] = $this->paramdefinitions;
        return($values);
    }

}
