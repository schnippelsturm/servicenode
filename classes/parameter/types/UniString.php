<?php

namespace ServiceNode\parameter\types;


/**
 * Description of String
 *
 * @author christian
 */
class UniString {
    //put your code here
    
     public function validateValue($value) {
        $result = null;
        try {
            $result = $this->prooftype($value);
            $this->proofNull($result);
            $this->proofcharlength($result);
        } catch (\Exception $ex) {
            trigger_error($ex->message, E_USER_ERROR);
            throw new \Exception("Validation Exception", 10, $ex);
        }
        return($result);
    }
    
    protected function proofNull($value) {
        if (($this->nullable === false) && (\is_null($value))) {
            throw new \Exception("not null contraint violated", 0, null);
        }
    }

    protected function proofcharlength($value) {
        if (\strlen($value) > $this->character_length) {
            //pruefen, ob bytelaenge oder Zeichenlaenge
            throw new \Exception("character length contraint violated", 1, null);
        }
    }
    
}
