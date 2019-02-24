<?php
namespace ServiceNode\Client;

class HTTPClient {

    protected $default_method = ServiceNode\protocol\http\HTTP_GET;
    protected $protocol_version = '1.1';
    protected $protocol = 'http';
    protected $cookies = array();
    protected $acceptCookies = true;
    protected $sendreferer=true;
    
     public function __construct($acceptCookies = true, $sendreferer = true) {
        $this->acceptCookies=$acceptCookies;
        $this->sendreferer=$sendreferer;

    }

    public function readRequest($request) {
      if ($request instanceof \ServiceNode\protocol\http\HTTPRequest)  {
        $header=$request->getHeader();
        $opts = array(
            'http' => array(
                'protocol_version' => $this->protocol_version,
                'method' => $request->getMethod(),
                'header' => array(
                    'content-type: ' .$header['content-type'],
                    'cookie: ' . $this->prepareCookie($request),
                    'content' => $request->getPostParams(),
                    'referer' => $request->getUrl(),
                    'connection: keepalive'
                ),
                'user_agent' => 'ServerNode Client'
            )
        );
       $context=\stream_context_create($opts);
       return(\file_get_contents($request->getUrl(), false, $context));
     }
    }
    
  
    
}

?>
