<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPRequest
 *
 * @author christian  */

class MultiPartMessageReader  {
    
    public function __construct() {
    }

    protected function extractParts($message,$boundary) {
        $pattern="/--".$boundary."\r\n(.*)\r\n--".$boundary."--\r\n/s";
        if (\preg_match_all($pattern, $message, $matches)) {
           // $matches[]
        }
        $result = array();
        $values = \explode("\r\n", $svalue);
        foreach ($values as $value) {
            $header = \explode(':', $value, 2);
            $result[$header[0]] = $header[1];
        }
        return($result);
    }
      

  
}
