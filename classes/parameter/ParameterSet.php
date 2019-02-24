<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\parameter;

/**
 * Description of ParameterSet
 *
 * @author christian
 */
class ParameterSet implements \JsonSerializable {
    //put your code here
    protected  $parameter=array();
    protected  $definiton=null;


    public function __construct(&$parameterSetDefinition) {
       if ($parameterSetDefinition instanceof ParameterSetDefinition) { 
            $this->definiton=$parameterSetDefinition;
       } 
    }
    
    public function getValue($name) {
        return($this->parameter[$name]);
    }
    
    public function setValue($name,$value) {
        $this->parameter[$name]=$value;
    }
    
    public function getType($name) {
        $this->definiton->
        return($this->parameter[$name]);
    }
    
    
    public function removeParameterDef() {
        
    }

    public function jsonSerialize() {
       $values=array();
       $values['paramter']=$this->parameter;
       $values['definition']=$this->definiton;
       return($values);
    }

}
