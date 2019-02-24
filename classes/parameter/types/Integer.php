<?php

namespace ServiceNode\parameter\types;


/**
 * Description of Integer
 *
 * @author christian
 */
class Integer {
    //put your code here
    
    public function validValue($value) {
        $result = null;
        try {
            $result = $this->prooftype($value);
            $this->proofNull($result);
        } catch (\Exception $ex) {
            trigger_error($ex->message, E_USER_ERROR);
            throw new \Exception("Validation Exception", 10, $ex);
        }
        return($result);
    }
    
     protected function proof($value) {
        $result = null;
        if (\is_int($value)) {
            $result = (int) $value;
        }
        if (\is_string($value) && (\is_numeric($value))) {
            $result = intval($value);
        }
        return($result);
    }
}
