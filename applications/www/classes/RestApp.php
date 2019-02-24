<?php

namespace ServiceNode\Application\www;

/**
 * Description of Example
  *
 * @author christian
 */
class RestApp extends \ServiceNode\protocol\http\RESTServlet implements \ServiceNode\protocol\http\HTTPMethod {

    protected $filestore = null;
    protected $parameterDefinitionSet = null;

    public function __construct($path,$parameterDefintionSet=null) {
        parent::__construct($path);
        $this->filestore = new \ServiceNode\storage\file\FileStore($path);
        if ($parameterDefintionSet instanceof \ServiceNode\parameter\ParameterSetDefinition) {
            $this->parameterDefintionSet = $parameterDefintionSet;
        }
    }

    public function __destruct() {
        unset($this->filestore);
    }

    public function handleRequest($request = null) {
        return($this->doRequest($request));
    }

    protected function doRequest($request) {
        try {
            $content = $this->test($request);
            $response = new \ServiceNode\protocol\http\HTTPResponse($content);
            $this->setDefaultResponseHeader($response);
        } catch (HTTPException $e) {
            \trigger_error(\E_USER_ERROR, \var_export($e, true));
            $response = new HTTPResponse(null);
            $response->setReturnCode($e->getCode());
        } catch (\Exception $e) {
            $response = new HTTPResponse(null);
            $response->setReturnCode(500);
            \trigger_error(\E_USER_ERROR, \var_export($e, true));
        }
        return($response->getResponseMessage());
    }

    protected function onConnect($request) {
        return($this->doRequest($request));
    }

    // DELETE
    protected function onDelete($request) {
        return($this->doRequest($request));
    }

    // READ 
    protected function onGet($request) {
        return($this->doRequest($request));
    }

    protected function onHead($request) {
        
    }

    protected function onInfo($request) {
        return($this->doRequest($request));
    }

    // create
    protected function onPost($request) {
         return($this->doRequest($request));
     //  $request=new \ServiceNode\protocol\http\HTTPRequest();
      // $request->getPostParams();
    }
    protected function getOptions() {
       // $this->parameterDefinitionSet
        foreach ($this->parameterDefinitionSet as $key => $value) {
             
        }
    }

    protected function onOptions($request) {
        return($this->getOptions($request));
        //  $request=new \ServiceNode\protocol\http\HTTPRequest();
        // $request->getPostParams();
    }

    // update/modify 
    protected function onPut($request) {
        return($this->doRequest($request));
    }

    protected function test($request) {
        $requestObj = new \ServiceNode\protocol\http\HTTPRequest($request);
        $params = $requestObj->getGetParams();
        $json = array();
        $json['name'] = 'Servicenode';
        $json['app'] = "RestApp";
        $json['version'] = '0.45.6';
        $json['double'] = 6.894;
        $json['params']=$params;
        return(\json_encode($json));
    }

}
