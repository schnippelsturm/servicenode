<?php
namespace ServiceNode\services;

interface IRequestHandler {

	public function handleRequest($request);
      //  public function readRequestStream($stream);
	//public function getKeepAliveTime();
	// public function setKeepAliveTime($keepalive_microseconds);
	
	
}

?>
