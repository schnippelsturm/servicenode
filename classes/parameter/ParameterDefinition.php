<?php

namespace ServiceNode\parameter;

/**
 * Description of ParameterDefinition
 *
 * @author christian
 */
class ParameterDefinition implements \JsonSerializable {

    protected $name;
    protected $type;
    protected $character_length = 0;
    protected $default_value = null;
    protected $nullable = false;
    protected $description = '';

    public function __construct($name, $type, $length, $default_value = null, $nullable = false, $desc = '') {
        $this->name = strtolower($name);
        $this->type = strtolower($type);
        $this->character_length = $length;
        $this->default_value = $default_value;
        $this->nullable = $nullable;
        $this->description = $desc;
    }

    public function validValue($value) {
        $result = null;
        try {
            $result = $this->prooftype($value);
            $this->proofNull($result);
            $this->proofcharlength($result);
        } catch (\Exception $ex) {
            \trigger_error($ex->message, E_USER_ERROR);
            throw new \Exception("Validation Exception", 10, $ex);
        }
        return($result);
    }

    protected function proofNull($value) {
        if (($this->nullable === false) && (\is_null($value))) {
            throw new \Exception("not null contraint violated", 0, null);
        }
    }
    
    protected function proofObject($value) {
        if (($this->nullable === false) && (\is_null($value)) && ($value instanceof $this->type)) {
            throw new \Exception("not null contraint violated", 0, null);
        }
    }

    protected function proofcharlength($value) {
        if (\strlen($value) > $this->character_length) {
            //pruefen, ob bytelaenge oder Zeichenlaenge
            throw new \Exception("character length contraint violated", 1, null);
        }
    }

    protected function prooftype($value) {
        $result = null;
        if ($this->type == 'integer') {
            $result = $this->proofInt($value);
        }
        if (($this->type == 'boolean') && \is_bool($value)) {
            $result = (bool) $value;
        }
        if (($this->type == 'float') || ($this->type == 'double')) {
            $result = $this->proofDouble($value);
        }
        if (($this->type == 'timestamp')) {
            $result = $this->proofInt($value);
        }
        if (($this->type == 'date')) {
            $result = $this->proofInt($value);
        }
        if (($this->type == 'datetime')) {
            $result = $this->proofInt($value);
        }
        if (($this->type == 'string') && (!\is_null($value))) {
            $result = \filter_var($value,FILTER_UNSAFE_RAW);
        }
        if (($this->type == 'array') && (!\is_null($value)) && \is_array($value) && \count($value)>0) {
            $result = $value;
        }
        if ((\class_exists($this->type)) && (!\is_null($value)) && ($value instanceof $this->type)) {
            $result =$this->proofObject($value);
        }
        return $result;
    }

    protected function proofInt($value) {
        $result = null;
        if (\is_int($value)) {
            $result = (int) $value;
        }
        if (\is_string($value) && (\is_numeric($value))) {
            $result = intval($value);
        }
        return($result);
    }

    protected function proofDouble($value) {
        $result = null;
        if (\is_double($value)) {
            $result = (double) $value;
        }
        if (\is_string($value) && (\is_numeric($value))) {
            $result = doubleval($value);
        }
        return($result);
    }

    protected function proofDate($value) {
        $result = null;
        if (\is_($value)) {
            $result = (int) $value;
        }
        if (\is_string($value)) {
            $result = intval($value);
        }
        return($result);
    }

    public function canCast(&$parameter) {
        $result = false;
        try {
            if (!\is_object($parameter)) {
                $newvalue = $this->validValue($parameter);
                $result = true;
            }
            if ($parameter instanceof Parameter) {
                $newvalue = $this->validValue($parameter->getValue());
                $result = true;
            }
        } catch (\Exception $ex) {
            $result = false;
            $message = __CLASS__ . ' can not cast ' . \var_export($parameter, true);
            $message.="\r\n Exception [" . $ex->getCode() . "   ]." . $ex->getMessage();
            $message.="\r\n " . $ex->getMessage();
            $message.="\r\n Stacktrace: [[" . $ex->getTraceAsString() . "]] \r\n";
            \trigger_error(E_USER_ERROR, $message);
        }
    }

    public function isRequired() {
        return(($this->nullable == false) && (is_null($this->default_value)));
    }

    public function getDescription() {
        return($this->description);
    }

    public function getType() {
        return($this->type);
    }

    public function getName() {
        return($this->name);
    }

    public function getDefaultValue() {
        return($this->default_value);
    }

    public function getCharacterlength() {
        return($this->character_length);
    }

    public function jsonSerialize() {
       $values=array();
       $values['name']=$this->name;
       $values['type']=$this->type;
       $values['nullable']=$this->nullable;
       $values['length']=$this->character_length;
        $values['default_value'] = $this->default_value;
       return($values);
    }

}
