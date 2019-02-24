<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTP
 * @author christian
 * 
 */


class HTTPCONNECT implements HTTPMethod {

	protected $request = null;
	protected $response = null;
	protected $document_root = '/';

	public function __construct($rootDir) {
		$this->document_root = getcwd();
		if (\is_dir($rootDir)) {
			$this->document_root = $rootDir;
		}
	}

	protected function getDocPath($url) {
		$path = \pathinfo($url, PATHINFO_DIRNAME);
		//$path=urldecode($url,PHP_URL_PATH);
		if ($path === false) {
			//# TODO throw 404 or 500 Exception
			$path = null;
		}
		if ($path != null) {
			$path = $this->document_root . DIRECTORY_SEPARATOR . $path;
		} else {
			$path = $this->document_root;
		}
		return(\realpath($path));
	}

	public function handleRequest($httpRequest) {
		$url = $httpRequest->getUrl();
		$path = $this->getDocPath($url);
		$filename = $path . DIRECTORY_SEPARATOR . basename($url);
		$content = \file_get_contents($filename);
		$response = new HTTPResponse($content);
		$mimetypeHandler = new \ServiceNode\mimetypes\MimeTypeBaseHandler();
		$defaultMimeTypeOfFile = $mimetypeHandler->getDefaultMimeTypeOfFile($filename);
		$response->addHeader('Date', \gmdate('D, d M Y H:i:s \G\M\T', \time()));
		$response->addHeader('Server', 'Servicenode Webserver');
		$response->addHeader('Cache-Control', 'no-cache, no-store, public');
		$response->addHeader('Pragma', 'no-cache');
		$response->addHeader('Content-Type', $defaultMimeTypeOfFile);
		$response->addHeader('Content-Length', \strlen($content));
		return($response->getResponseMessage());
	}

}
