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
class ErrorhandlerFacade implements \servicenode\exceptions\IExceptionHandler {

    private $errorhandler = null;

    public function __construct($class_name) {
        $this->errorhandler = ErrorhandlerFactory::getErrorhandler($class_name);
    }

    public function serverErrorHandler($code, $message, $file, $line) {
        $res = null;
        if (!\is_null($this->errorhandler)) {
            $res = $this->errorhandler->serverErrorHandler($code, $message, $file, $line);
        }
        return($res);
    }

    public function register() {
        \set_error_handler(array($this->errorhandler, 'serverErrorHandler'));
    }

    public function unregister() {
        \restore_error_handler();
    }

    public function __destruct() {
        $this->unregister();
        unset($this->errorhandler);
        $this->errorhandler = null;
    }

}
