<?php

namespace ServiceNode\sockets;

class BasicClient {

    const TRANPORTPROTOCOLS = array('tcp', 'unix', 'ssl','http');

    protected $protocol = '';
    protected $host = '';
    protected $port = null;
    protected $socket_timeout = null;
    protected $isEncrypted = false;
    protected $path='/';

    public function __construct($host, $port,$path='/', $transportProtocol = 'http') {
        $this->host = $this->getValidHost($host);
        $this->port = (int) $port;
        $this->path = $path;
        $this->protocol = $this->getValidTransportProtocol($transportProtocol);
        $this->socket_timeout = \ini_get("default_socket_timeout");
        $this->init();
    }

    protected function init() {
        $this->isEncrypted = $this->isSSLProto();
    }
    
    
    protected function getEndpoint() {
        //$endpoint = $this->protocol . '://' . $this->host.'/';
        $endpoint = $this->protocol . '://' . $this->host. ':' . $this->port.'/'.$this->path;
        if ($this->protocol == 'unix') {
            $endpoint = $this->protocol . '://' . $this->host;
        }
        return($endpoint);
    }

    protected function getValidHost($host) {
        // 127.0.0.1
        $result = false;
        $pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
        if (!\preg_match($pattern, \trim($host))) {
            $ip = \gethostbyname(\trim($host));
            if ((\ip2long($ip) != -1) && (\preg_match($pattern, $ip))) {
                $result = \trim($ip);
            }
        } else if (\ip2long(\trim($host)) != -1) {
            $result = \trim($host);
        }
        return($result);
    }

    protected function isSSLProto() {
        $pattern = '/^([ssl|sslv\d]{1}|[tsl|tslv\d\.\d]{1})$/';
        return(\preg_match($pattern, $this->protocol));
    }
    
    protected function getSelectedSSLMethod($proto) {
            if ($proto=='sslv2') {
                $result=\STREAM_CRYPTO_METHOD_SSLv2_CLIENT;
            }
            if ($proto=='sslv23') {
                $result=\STREAM_CRYPTO_METHOD_SSLv23_CLIENT;
            }
            if ($proto=='sslv3') {
                $result=\STREAM_CRYPTO_METHOD_SSLv3_CLIENT;
            }
            if ($proto=='tslv1') {
                $result=\STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT;
            }
            if ($proto=='tslv1.1') {
                $result=\STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT;
            }
            if ($proto=='tslv1.2') {
                $result=\STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }      
      return($result);
    }

    protected function getSSLMethod() {
        $matches=array();
        $result=\STREAM_CRYPTO_METHOD_TLS_CLIENT;
        $pattern = '/^([ssl|sslv\d]{1}|[tsl|tslv\d\.\d]{1})$/';
        if (\preg_match($pattern, $this->protocol, $matches)) {
            $proto=$matches[1];
            $result=$this->getSelectedSSLMethod($proto);   
        }
      return($result);
    }

    protected function getValidTransportProtocol($protocol) {
        $validProto = 'tcp';
        if (\in_array(\trim(\strtolower($protocol)), self::TRANPORTPROTOCOLS)) {
            $validProto = \trim(\strtolower($protocol));
        }
        return($validProto);
    }

    protected function getContext() {
        $opts = array();
        if ($this->isEncrypted) {
            $opts['ssl'] = $this->getSSLOpts($cert = null);
        }
      // $opts['socket'] = array('bindto' => $this->host.':'.$this->port);
        $opts['http']=array(
                'protocol_version' =>'1.1',
                'method' => 'GET',
          //      'path' => $this->path,
                'header' => array(
                    'connection: close'
                ),
                'user_agent' => 'ServerNode Client'
           //     'timeout' => $this->
            );
        return(\stream_context_create($opts));
    }

    protected function getSSLOpts($cert) {
        $opt = array();
        $opt['verify_host'] = true;
        if (!empty($cert)) {
            $opt['cafile'] = $cert;
            $opt['verify_peer'] = true;
        } else {
            $opt['allow_self_signed'] = true;
        }
        return($opt);
    }
	
    
    public function readRequest() {
        return(\file_get_contents($this->getEndpoint(),false,$this->getContext()));
    }
  
  


}

?>
