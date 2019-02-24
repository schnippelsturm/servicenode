<?php

namespace ServiceNode\parameter\types;


/**
 * Description of Date
 *
 * @author christian
 */
class Date {

    //put your code here

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

}
