<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\protocol;

/**
 * Description of URL
 *
 * @author christian
 */
class URL {
  protected $path;
  protected $scheme;
  protected $host;
  protected $port;
  protected $query;
  protected $user;
  protected $password;
  protected $fragment;
  
  
  public function __construct($url) {
    try {
         $this->scheme=\parse_url($url,\PHP_URL_SCHEME);
         $this->path=\parse_url($url,\PHP_URL_PATH);
         $this->host=\parse_url($url,\PHP_URL_HOST);
         $this->port=\parse_url($url,\PHP_URL_PORT);
         $this->query=\parse_url($url,\PHP_URL_QUERY);
         $this->user=\parse_url($url,\PHP_URL_USER);
         $this->password=\parse_url($url,\PHP_URL_PASS);
         $this->fragment=\parse_url($url,\PHP_URL_FRAGMENT);
    } catch(\Exception $ex) {
        \trigger_error("HTTP URL-parse:".$ex->getMessage());
        throw new \ServiceNode\protocol\http\HTTPException('',500,$ex);
    } 
  }

  function __get($name) {
      if (\property_exists(__CLASS__, $name)) {
          return($this->$name);
      }
  }

}
