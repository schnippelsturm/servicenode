<?php

namespace ServiceNode;

/**
 * Description of Server
 *
 * @author christian
 */
class Server implements \ServiceNode\sockets\IServerInterface {

    const STATUS = 'status';
    const START = 'start';
    const RESTART = 'restart';
    const STOP = 'stop';

    protected $addr;
    protected $port;
    protected $sockettype = 'tcp';
    protected $service;
    protected $requesthandler = null;
    protected $errorhandler = null;

    public function __construct($addr, $port, &$protocol, $sockettype = 'tcp') {
        $this->port = $port;
        $this->addr = $addr;
        $this->sockettype = $sockettype;
        $this->requesthandler = &$protocol;
        $this->errorhandler = new \ServiceNode\exceptions\Errorhandler("servicenode_error.log");
        $this->errorhandler->register();
    }

    public function __destruct() {
        $this->errorhandler->unregister();
        unset($this->errorhandler);
    }

    public function getStatus() {
        $status = null;
        try {
            $service = new \ServiceNode\sockets\ServerSocket($this->addr, $this->port, $this->sockettype, false);
            $service->setRequestHandler($this->requesthandler);
            $status = $service->getStatus();
            unset($service);
        } catch (\Exception $ex) {
            \trigger_error($ex->getMessage(), \E_USER_ERROR);
        }
        return($status);
    }

    protected function restart() {
        $this->stop();
        sleep(1);
        $this->start();
    }

    public function getStateInfoString($result) {
        $str = ' is not running ';
        if (\is_int($result)) {
            $str = 'service is running [' . $result . ']';
        }
        if ($result === true) {
            $str = ' [successful] ';
        }
        if ($result === false) {
            $str = ' [failed] ';
        }
        return($str);
    }

    public function start() {
        $result = false;
        try {
            $service = new \ServiceNode\sockets\ServerSocket($this->addr, $this->port, $this->sockettype,false);
            $service->setRequestHandler($this->requesthandler);
            $status = $service->getStatus();
            if ((\is_null($status)) || ($status === false)) {
                $service->start();
            }
            $result = $this->getStatus();
        } catch (\Exception $ex) {
            $result = false;
            \trigger_error($ex->getMessage(), \E_USER_ERROR);
        }
        return($result);
    }

    public function stop() {
        $result = true;
        try {
            $service = new \ServiceNode\sockets\ServerSocket($this->addr, $this->port, $this->sockettype, false);
            $service->setRequestHandler($this->requesthandler);
            $status = $service->getStatus();
            if (!\is_null($status)) {
                $service->stop();
            }
            $servicePID = $this->getStatus();
            $result = \is_null($servicePID);
        } catch (\Exception $ex) {
            $result = false;
            \trigger_error($ex->getMessage(), \E_USER_ERROR);
        }
        return($result);
    }

    public function handleEvent($event) {
        try {
            if ($event == self::START) {
                return($this->start());
            }
            if ($event == self::RESTART) {
                return($this->restart());
            }
            if ($event == self::STOP) {
                return($this->stop());
            }
            if ($event == self::STATUS) {
                return($this->getStatus());
            }
        } finally {
            \flush();
        }
    }

}
