<?php

namespace ServiceNode\Configuration;

/**
 * Description of ServerSocketConfiguration
 *
 * @author christian
 */

class ServerSocketConfiguration {
	protected $addr = '';
	protected $port = 8080;
	protected $protocol = 'tcp';
	protected $limit = 100;
	protected $maxchilds = 30;
	protected $socket_timeout = -1; // 1000;
	protected $protocol_handler = null;

	protected function getExceptionMessage($message) {
		$s = __CLASS__ . '::' . __METHOD__;
		$s.=$message;
		return($s);
	}

	public function getAddr() {
		return($this->addr);
	}

	public function setAddr($addr) {
		$this->address = $addr;
	}

	public function getPort() {
		return($this->port);
	}

	public function setPort($port) {
		if (is_int($port) === false) {
			throw new ServiceNodeConfigurationException($this->getExceptionMessage(PORT_CONFIGURATION_EXCEPTION_MSG), PORT_CONFIGURATION_EXCEPTION_CODE, null);
		}
		$this->port = $port;
	}

	public function getMaxPreforkedChilds() {
		return($this->maxchilds);
	}

	public function setMaxPreforkedChilds($maxchilds) {
		if (is_int($maxchilds) === false) {
			throw new ServiceNodeConfigurationException($this->getExceptionMessage(MAXCHILD_CONFIGURATION_EXCEPTION_MSG), MAXCHILD_CONFIGURATION_EXCEPTION_CODE, null);
		}
		$this->maxchilds = $maxchilds;
	}

	public function getSocketTimeout() {
		return($this->maxchilds);
	}

	public function setSocketTimeout($timeout) {
		if (is_int($timeout) === false) {
			throw new ServiceNodeConfigurationException($this->getExceptionMessage(MAXCHILD_CONFIGURATION_EXCEPTION_MSG), MAXCHILD_CONFIGURATION_EXCEPTION_CODE, null);
		}
		$this->timeout = $timeout;
	}

	public function setProtocolHandler($protocolHandler) {
		$this->protocol_handler = $protocolHandler;
	}

	public function getProtocolHandler() {
		return($this->protocol_handler);
	}

}
