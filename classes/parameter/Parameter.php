<?php

namespace ServiceNode\parameter;

/**
 * Description of Parameter
 *
 * @author christian
 */
class Parameter implements \JsonSerializable {

    protected $name = '';
    protected $type = '';
    protected $value = '';
    protected $options = array();
    protected $paramdefinition = null;

    public function __construct(&$paramdefinition) {
        if ($paramdefinition instanceof ParameterDefinition) {
            $this->paramdefinition = $paramdefinition;
        }
    }

    protected function setParameterDefinition(&$paramdefinition) {
        if ($paramdefinition instanceof ParameterDefinition) {
            $this->paramdefinition = $paramdefinition;
        }
    }

    public function setValue($avalue) {
        $this->value = $this->paramdefinition->validValue($avalue);
    }

    public function getValue() {
        return($this->value);
    }

    public function getName() {
        return($this->name);
    }

    public function getType() {
        return($this->type);
    }

    public function getOptions() {
        return($this->options);
    }

    public function castTo(&$parameterdefinition) {
        $castvalue = $this->value;
        if ($parameterdefinition instanceof ParameterDefinition) {
            if ($parameterdefinition->canCast($castvalue)) {
                $this->paramdefinition = $parameterdefinition;
                $this->setValue($castvalue);
            }
        }
    }

    public function castFrom(&$parameter) {
        if ($this->paramdefinition->canCast($parameter)) {
            $value = null;
            if ($parameter instanceof Parameter) {
                $value = $parameter->getValue();
            } else {
                $value = $parameter;
            }
            $this->setValue($value);
        }
    }

    public function getParameterDefinition() {
        return($this->paramdefinition);
    }

    public function jsonSerialize() {
       $values=array();
       $values['name']=$this->name;
       $values['type']=$this->type;
       $values['definition']=$this->paramdefinition;
       $values['value']=$this->value;
       $values['length']=$this->character_length;
       //$this->description
       return($values);
    }



}
