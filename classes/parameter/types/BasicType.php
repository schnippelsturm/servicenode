<?php

namespace ServiceNode\parameter\types;

/**
 * Description of BasicType
 *
 * @author christian
 */
class BasicType {
    //put your code here
    
    
    protected function isNull($value) {
        if (($this->nullable === false) && (\is_null($value))) {
            throw new \Exception("not null contraint violated", 0, null);
        }
    }
    
}
