<?php

namespace ServiceNode\Application\www;

/**
 *  *
 * @author christian
 */
class ExampleAppl  extends \ServiceNode\protocol\http\PHPServlet implements \ServiceNode\protocol\http\HTTPMethod {
    protected $template='';
    protected $filestore=null;


    public function __construct($path) {
        parent::__construct($path);
         $this->filestore=new \ServiceNode\storage\file\FileStore($path);
         $filename= \realpath($path).\DIRECTORY_SEPARATOR."templates".\DIRECTORY_SEPARATOR."index.html";
         $this->template=$this->filestore->read($filename);
    }
        
    public function __destruct() {
        unset($this->filestore);
    }
    
    public function handleRequest($request = null) {
        return($this->doRequest($request));
    }
    
    protected function doRequest($request) {
      try {
            $content=$this->template;   
            $content=  \preg_replace('/<!--content.page-->/', \var_export($request,true), $content);
            $response = new \ServiceNode\protocol\http\HTTPResponse($content);
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
            $response->addHeader('Content-Type', "text/html");
            $response->addHeader('Content-Length', \strlen($content));
            $response->addHeader('Connection', 'keep-alive');
            $response->addHeader('Keep-Alive','timeout=120 / max=25');
        } catch (HTTPException $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getCode());
        } catch (\Exception $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode(500);
        }
        return($response->getResponseMessage()); 
}

    protected function onConnect($request) {
        
    }

    protected function onDelete($request) {
        
    }

    protected function onGet($request) {
         return(doRequest($request));
    }

    protected function onHead($request) {
        
    }

    protected function onInfo($request) {
        
    }

    protected function onPost($request) {
        
    }

    protected function onPut($request) {
        
    }

}
