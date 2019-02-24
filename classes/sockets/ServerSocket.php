<?php

namespace ServiceNode\sockets;

class ServerSocket extends \ServiceNode\Process\MultiProcessController implements IServerInterface {

    protected $addr = '';
    protected $port = null;
    protected $socket = null;
    protected $protocol = 'tcp';
    protected $requestHandler = null;
    protected $socket_timeout = 1000;
    protected $keepalive_timeout = 15000;

    public function __construct($socketaddr, $socketport = 80, $socketprotocol = 'tcp', $daemon = false) {
        parent::__construct('servicenode', 10, $daemon);
        $this->addr = $socketaddr;
        $this->port = $socketport;
        $this->protocol = $socketprotocol;
        $this->socket_timeout = \ini_get("default_socket_timeout");
        $this->init();
    }

    public function __destruct() {
        parent::__destruct();
    }

    protected function getEndpoint() {
        $endpoint = $this->protocol . '://' . $this->addr . ':' . $this->port;
        if ($this->protocol == 'unix') {
            $endpoint = $this->protocol . '://' . $this->addr;
        }
        return($endpoint);
    }

    protected function createSocket() {
        try {
            $errno = 0;
            $errstr = '';
            $this->socket = \stream_socket_server($this->getEndpoint(), $errno, $errstr);
            if (!\is_resource($this->socket)) {
                throw new \Exception("could not create socket " . $errstr . "(" . $errno . ")" . \PHP_EOL);
            }
        } catch (\Exception $e) {
            $this->socket = null;
            throw new \Exception("could not create socket. " . \PHP_EOL . " Exception " . $e->getMessage() . \PHP_EOL);
        }
    }

    public function setRequestHandler(&$requestHandler) {
        if ($requestHandler instanceof \ServiceNode\services\IRequestHandler) {
            $this->requestHandler = $requestHandler;
        }
    }

    public function getPidFileName() {
        $filename = $this->servicename . '_';
        if ($this->protocol == 'unix') {
            $pinfo = \pathinfo($this->addr);
            $filename = $this->protocol . '_' . \basename($this->addr, '.' . $pinfo['extension']);
            $filename .= '_';
        } else {
            $filename .= $this->protocol . '_' . $this->port;
        }
        $filename.='.pid';
        return(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $filename);
    }

    protected function handleRequest($request, $peername) {
        $result = '';
        //      \syslog(LOG_INFO, 'Server has received from ' . $peername . ' this : ' . $request . "\r\n");
        //     \syslog(LOG_INFO, 'Server responses to  ' . $peername . ' this : ' . $result . "\r\n");

        if ($this->requestHandler instanceof \ServiceNode\services\IRequestHandler) {
            $result = $this->requestHandler->handleRequest($request);
            //\syslog(\LOG_INFO, 'Server responses to  ' . $peername . ' this : ' . $result . "\r\n");
        }
        return($result);
    }

    protected function readRequest($conn) {
        $request = '';
        if (\is_resource($conn)) {
            $sock = \socket_import_stream($conn);
            $read = true;
            $buffer = '';
            while ($read) {
                $buffer = \socket_read($sock, 8192, \PHP_BINARY_READ);
                $read = (!empty($buffer) && ($buffer !== false));
                if ($read) {
                    $request.=$buffer;
                }
            }
        }
        return($request);
    }

    public function start() {
        $this->closeSTDFileHandles();
        $result = true;
        try {
            $this->createSocket();
            if (!\is_null($this->socket)) {
                \stream_set_blocking($this->socket, false);
                $this->startWork();
                while (($this->shutdownSignal === false) && ($this->bparent==true)) {
                    \pcntl_signal_dispatch();
                    $result = $this->startWork();
                }
            }
        } catch (Exception $e) {
            $result = false;
            //  \syslog(LOG_ERR, 'Exception  : ' . $e->getMessage() . "\r\n");
            \trigger_error('Expection X ' . $e->getMessage() . \PHP_EOL, E_USER_ERROR);
        }
        return($result);
    }

    protected function freeResources() {
        if (\is_resource($this->socket) && ($this->bparent === true)) {
            \stream_socket_shutdown($this->socket, \STREAM_SHUT_RDWR);
            \fclose($this->socket);
            unset($this->socket);
            if (($this->protocol == 'unix') && \file_exists($this->addr)) {
                \unlink($this->addr);
            }
            $this->socket = null;
        }
        parent::freeResources();
    }
    
    
    protected function runParent($pid) {
        parent::runParent($pid);
        \pcntl_signal_dispatch();
        if ($this->shutdownSignal === false) {
            \trigger_error("children :".\var_export($this->getmyChildren(),true),\E_USER_NOTICE);
        }            
    }

    protected function runChild() {
        $this->init();
        while ($this->shutdownSignal === false) {
            $childstatus = $this->acceptConnection();
        }
        exit($childstatus);
    }

    protected function socketSelect($conn, $peername) {
        $rsocketlist = array($conn);
        $wsocketlist = null;
        $exsocketlist = array($conn);
        $request = '';
        if (false !== ($num_changed_streams = \stream_select($rsocketlist, $wsocketlist, $exsocketlist, 0, 10000))) {
            if ($num_changed_streams > 0) {
                $request = $this->readRequest($conn);
                \stream_set_blocking($conn, 1);
                $result = $this->handleRequest($request, $peername);
                $this->stream_write($conn, $result);
                \stream_set_blocking($conn, 0);
            }
        }
    }

    protected function stream_write($conn, $content) {
        $written = true;
        $bytes = 0;
        while (($written == true) && (\is_resource($conn))) {
            $written = \fwrite($conn, \substr($content, $bytes, 8192), 8192);
            if ($written == 0) {
                $written = false;
            }
            if ($written != false) {
                $bytes+=$written;
            }
        }
        if (\is_resource($conn)) {
            \fflush($conn);
        }
    }

    protected function acceptConnection() {
        $peername = '';
        $res = 0;
        try {
            \pcntl_signal_dispatch();
            $conn = \stream_socket_accept($this->socket, $this->socket_timeout, $peername);
            if (\is_resource($conn)) {
                \stream_set_blocking($conn, 0);
                $this->socketSelect($conn, $peername);
            } else {
                \usleep(10);
            }
        } catch (\Exception $e) {
            $res = 1;
            \trigger_error('Expection ' . $e->getMessage() . \PHP_EOL, E_USER_ERROR);
        }
        return($res);
    }

}

?>
