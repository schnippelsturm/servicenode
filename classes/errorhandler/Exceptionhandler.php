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
class Exceptionhandler implements \servicenode\exceptions\IExceptionHandler {

    private $filestore = null;

    public function __construct() {
        $logfile = \sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'servicenode_error.log';
        $this->filestore = new \ServiceNode\storage\file\LogFileStore($logfile);
    }

    private function formatMessage(&$code, &$message, &$file, &$line) {
        $message = '[PID:' . \getmypid() . '][' . \date("c", \time()) . '] [Code:' . $code . ' Message:' . $message;
        $message.="]\r\n on [" . $file . ':line ' . $line . "]\r\n";
        return($message);
    }

    function serverErrorHandler($code, $message, $file, $line) {
        $result = true;
        try {
            $logmessage = $this->formatMessage($code, $message, $file, $line);
            $result = $this->filestore->add($logmessage);
        } catch (\Exception $ex) {
            /// \trigger_error($ex->getMessage());
            $result = false;
        }
        return($result);
    }

    public function register() {
        \set_exception_handler(array($this, 'serverErrorHandler'));
    }

    public function unregister() {
        \restore_exception_handler();
    }

}
