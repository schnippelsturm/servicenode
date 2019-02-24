<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 *
 * @author christian
 * 
 */


class HTTPInfo implements HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '';
    protected $fileStore = null;

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->fileStore = new \ServiceNode\storage\file\FileStore($this->document_root);
    }

    public function __destruct() {
        unset($this->fileStore);
    }

    protected function getDocPath($url) {
        $path = \pathinfo($url, PATHINFO_DIRNAME);
        if ($path === false) {
            $path = null;
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, null);
        }
        if ($path != null) {
            $path = $this->document_root . DIRECTORY_SEPARATOR . $path;
        } else {
            $path = $this->document_root;
        }
        return(\realpath($path));
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

    public function setResponseHeader($response) {
        if ($response instanceof HTTPResponse) {
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Server', 'Servicenode Webserver');
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
            $response->addHeader('Content-Type', $defaultMimeTypeOfFile);
            $response->addHeader('Content-Length', \strlen($content));
            $response->addHeader('Connection', 'keep-alive');
        }
    }

    public function handleRequest($httpRequest) {
        try {
            $content = $httpRequest->getRawRequest();
            $response = new HTTPResponse($content);
            $response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
            $response->addHeader('Server', 'Servicenode Webserver');
            $response->addHeader('Cache-Control', 'no-cache, no-store, public');
            $response->addHeader('Pragma', 'no-cache');
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
