<?php

namespace ServiceNode\parameter;

/**
 * Description of ParameterSetDefinition
 *
 * @author christian
 */
class ParameterSetDefinition implements \JsonSerializable {
    protected $name = '';
    protected $paramdefinitions = array();
    protected $description='';

    public function __construct($name,$description='') {
        $this->name=$name;
        $this->description=$description;
    }

    protected function addParameterDefinition($paramdefinition) {
        if ($paramdefinition instanceof ParameterDefinition) {
            $this->paramdefinitions[$paramdefinition->getName()] = $paramdefinition;
        }
    }
        
    public function isParameterDefined($name) {
        \trigger_error(\var_export($this->parameterDefinitionList,true),E_USER_NOTICE);
        return (\key_exists($name, $this->parameterDefinitionList)); 
    }
    
    protected function getParameterDefinition($name) {  
        $def=null;
        if (\key_exists($name,$this->paramdefinition)) {
            $def=$this->paramdefinitions[$name];
        }
        return($def); 
    }
     
    public function validValue($name, $value) {
        $result = null;
        try {
            $def=$this->getParameterDefinition($name);
            if (\is_null($def)) {
                $result = $def->prooftype($value);
            }
        } catch (\Exception $ex) {
            trigger_error($ex->message, E_USER_ERROR);
            throw new \Exception("Validation Exception", 10, $ex);
        }
        return($result);
    }
    
    protected function getParameterType($name) {  
        $type=null;
        if (\key_exists($name,$this->paramdefinition)) {
            $type=$this->paramdefinitions[$name]->getType();
        }
        return($type); 
    }
    
    protected function getParameterDefaultValue($name) {  
        $value=null;
        if (\key_exists($name,$this->paramdefinition)) {
            $value=$this->paramdefinitions[$name]->getDefaultValue();
        }
        return($value); 
    }
        

    protected function removeParameterDefinition($key) {
        if (\key_exists($key, $this->paramdefinitions)) {
            unset($this->paramdefinitions[$key]);
        }
    }
    
    
      public function isRequired($name) {
        $result = null;
            $def=$this->getParameterDefinition($name);
            if (\is_null($def)) {
                $result = $def->isRequired();
        } 
        return($result);
    }

    public function getParameterOption() {
        \reset($this->paramdefinitions);
        $options=array();
        foreach ($this->paramdefinitions as $key => $def) {
            $value = (string) $key.':';
            $def = new ParameterDefinition($name, $type);
            if ($def->isRequired()) {
                $value.=':';
            }
            $options[]=$value;
        }
        return($options);
    }
    
    
    public function getCommandline() {
        $cmd=' -cmd '.$this->name.' ';
        reset($this->paramdefinitions);
        foreach ($this->paramdefinitions as $key => $def) {
            $value = '-'.$key.' <'.$key.'>';
            if ($def->isRequired()==false) {
                $value=' [-'.$key.' <'.$key.'>]';
            }
            $cmd.=' '.$value;
        }
        return($cmd);
    }
    
    public function getDescription() {
        $desc=array();
        $desc[]=$this->getCommandline();
        $desc[]=$this->name.": \r\n".$this->description." \r\n\r\n";
        \reset($this->paramdefinitions);
        foreach ($this->paramdefinitions as $key => $def) {
            $len=$def->getCharacterlength(); 
            $value = '-'.$key.' <'.$key."> \r\n";
            $value.= ' defines '.$def->getName().' of '.$def->getType();
            if (!\is_null($len)) {
                $value.='('.$len.')';
            }
            if (!\is_null($def->getDefaultValue())) {
                $value.=' default value='.$def->getDefaultValue();
            }
            if ($def->isRequired()==true) {
                $value.=' required ';
            }
            $desc[].=' '.$value;
        }
        return(\implode(" \r\n\r\n",$desc));
    }

    public function jsonSerialize() {
       $values=array();
       $values['name']=$this->name;
       $values['parameterDefinitions']=$this->paramdefinitions;
       return($values);
    }

}
