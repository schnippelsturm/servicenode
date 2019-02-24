<?php

namespace ServiceNode\parameter\types;


/**
 * Description of Double
 *
 * @author christian
 */
class Double {

    //put your code here

    protected function proof($value) {
        $result = null;
        if (\is_double($value)) {
            $result = (double) $value;
        }
        if (\is_string($value) && (\is_numeric($value))) {
            $result = doubleval($value);
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

}
