<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\exceptions;

/**
 * Description of Errorhandler
 *
 * @author christian
 */
class ErrorhandlerFactory  {
    
      
    public static function getErrorhandler($class_name) {
        $errorhandler=null;
        if (\class_exists($class_name) && self::isErrorHandler($class_name)) {
          $errorhandler=new $class_name();   
        }
        return($errorhandler);
    }

    private static function isErrorHandler($class_name) {
        $class_implements = \class_implements($class_name);
        return (\key_exists('\servicenode\exceptions\IExceptionHandler',$class_implements));
    }
    
}



