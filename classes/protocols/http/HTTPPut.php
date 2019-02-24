<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 *
 * @author christian
 * 
 */

class HTTPPut implements HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $httpStore = null;

    public function __construct($rootDir) {
        $this->document_root = getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->httpStore = new \ServiceNode\protocol\http\HTTPStore($this->document_root);
    }

    public function __destruct() {
        unset($this->httpStore);
    }

    protected function getDocPath($url) {
        return($this->httpStore->getDocPath($url));
    }

    public function handleRequest($httpRequest) {
        try {
            $url = $httpRequest->getUrl();
            $path = $this->getDocPath($url);
            $filename = $path . DIRECTORY_SEPARATOR .\basename($url);
            $content = $httpRequest->getRawPost();
            $this->httpStore->save($filename, $content);
            $response = new HTTPResponse('Erfolg melden setzen..');
            $mimetypeHandler = new \ServiceNode\mimetypes\MimeTypeBaseHandler();
            $defaultMimeTypeOfFile = $mimetypeHandler->getDefaultMimeTypeOfFile($filename);
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Server', 'Servicenode Webserver');
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
            $response->addHeader('Content-Type', $defaultMimeTypeOfFile);
            $response->addHeader('Content-Length', \strlen($content));
        } catch (HTTPException $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getMessage());
        } catch (\Exception $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getMessage());
        }
        return($response->getResponseMessage());
    }

}

?>
