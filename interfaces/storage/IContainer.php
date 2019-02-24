<?php

namespace ServiceNode\storage;

/**
 * Description of Container
 *
 * @author christian
 */

interface IContainer {

	public function read($filename); 
	public function save($filename,$value); 
}
