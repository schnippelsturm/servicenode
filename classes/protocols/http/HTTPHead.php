<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 *
 * @author christian
 * 
 */

class HTTPHead implements HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $httpStore = null;

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
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

    protected function getFileContent($filename) {
        $content = null;
        try {
            $content = $this->fileStore->read($filename);
        } catch (\Exception $ex) {
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, $ex);
        }
        return($content);
    }

    public function handleRequest($httpRequest) {
        try {
            $url = $httpRequest->getUrl();
            $defaultMimeTypeOfFile = $this->httpStore->getDefaultMimeType($url);
            $content = $this->httpStore->readResource($url);
            $response = new HTTPResponse($content);
            $mimetypeHandler = new \ServiceNode\mimetypes\MimeTypeBaseHandler();
            $defaultMimeTypeOfFile = $mimetypeHandler->getDefaultMimeTypeOfFile($filename);
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Server', 'Servicenode Webserver');
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
            $response->addHeader('Content-Type', $defaultMimeTypeOfFile);
            $response->addHeader('Content-Length', \strlen($content));
            $response->addHeader('Connection', 'keep-alive');
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
