<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPEventRouter
 *
 * @author christian
 */
class HTTPRequestRouter implements \ServiceNode\protocol\http\HTTPMethod {

    protected $request = null;
    protected $response = null;
    protected $document_root = '/';
    protected $methodfac = null;
    protected $protocol = 'HTTP';
    protected $http = null;
    protected $registered_routes = array();

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->http = new HTTP($this->document_root);
        $this->methodfac = new HTTPMethodFactory($this->document_root);
    }

    public function addRoute($urlPath, $method) {
        if (($method instanceof PHPServlet) && (!\is_null($method))) {
            $this->registered_routes[$urlPath] = $method;
        }
    }

    public function isRouted($urlPath, $methodname = "GET") {
        /* $result = false;
          if (\key_exists($urlPath, $this->registered_routes)) {
          if (\is_array($this->registered_routes[$urlPath])) {
          $result = \in_array($method, $this->registered_routes[$urlPath]);
          } else {
          $result = ($this->registered_routes[$urlPath] == $method);
          }
          } */
        return(\key_exists($urlPath, $this->registered_routes));
    }

    public function callRoute($urlPath, $request) {
        $response = null;
        if ($this->isRouted($urlPath)) {
            $method = &$this->registered_routes[$urlPath];
            $response = $method->handleRequest($request);
        }
        return($response);
    }

    public function removeRoute($urlPath) {
        unset($this->registered_routes[$urlPath]);
    }

    private function doRequest($raw_request) {
        $request = new HTTPRequest($raw_request);
        $path = $request->getPath();
        $methodname = $request->getMethod();
        $method = $this->methodfac->getMethod($methodname);
        if (\is_null($method)) {
            \trigger_error("Not supported method:" . $methodname . "\r\n");
            /* $response = new HTTPResponse(null);
              $response->setReturnCode(\HTTP_RETURNCODE_METHOD_NOT_ALLOWED_405);
              return($response->getResponseMessage()); */
            throw new HTTPException(HTTPUtil::getReturnCodeMessage(405), 405, null);
        }
        if ($this->isRouted($path)) {
            return($this->callRoute($path, $raw_request));
        }
        \trigger_error($path . " is not routed. Request:" . \var_export($request, true) . "\r\n");
        $response = $this->http->handleRequest($raw_request);
        return($response);
    }

    public function handleRequest($raw_request) {
        $response = null;
        try {
            $response = $this->doRequest($raw_request);
        } catch (HTTPException $ex) {
            $httpResponse = new HTTPResponse(null);
            $httpResponse->setReturnCode($ex->getMessage());
            $response = $httpResponse->getResponseMessage();
        } catch (Exception $ex) {
            $httpResponse = new HTTPResponse(null);
            $httpResponse->setReturnCode($ex->getMessage());
            $response = $httpResponse->getResponseMessage();
        }
        return($response);
    }

    protected function getPath($routedPath) {
        //  $pathes=  array_keys($th)
        $path_dept = \explode("/", $routedPath);
        foreach ($this->registered_routes as $key => $value) {
            $pos=\strpos($routedPath, $key);
            if ($pos <= 0) {
                $path=substr($routedPath, $pos+1,  \strlen($routedPath)-pos);
            }
        }
    }

}
