<?php

namespace ServiceNode\Configuration;
/**
 * Description of ServerSocketConfiguration
 *
 * @author christian
 */
class ConfigurationItemException extends \Exception {
	
}

class ConfigurationItem {
	protected $name = null;
	protected $value = null;
	protected $type = null;

	
	public function __construct($name,$type,$value) {
	   try {
		 $this->setName($name);
		 $this->setType($type);
		 $this->setValue($value);
	   } catch (ConfigurationItemException $ex) {
		   throw new ConfigurationItemException();
	   }
	}
	
	protected function getExceptionMessage($message) {
		$s = __CLASS__ . '::' . __METHOD__;
		$s.=$message;
		return($s);
	}

	public function getName() {
		return($this->addr);
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getValue() {
		return($this->value);
	}

	public function setValue($value) {
		if ($this->isValidValue($value) === false) {
			throw new ConfigurationItemException($this->getExceptionMessage(PORT_CONFIGURATION_EXCEPTION_MSG), PORT_CONFIGURATION_EXCEPTION_CODE, null);
		}
		$this->value = $value;
	}

	public function getType() {
		return($this->type);
	}

	public function setType($type) {
		if ($this->isValidType($type) === false) {
			throw new ConfigurationItemException($this->getExceptionMessage(PORT_CONFIGURATION_EXCEPTION_MSG), PORT_CONFIGURATION_EXCEPTION_CODE, null);
		}
		$this->type = $type;
	}
	
	protected function isValidValue($value) {
		return (\is_null($value)===false);
	}
	
	protected function isValidType($type) {
	    return (\is_null($type)===false);
	}

}
