<?php

namespace ServiceNode\Parser;

/**
 * Description of PHPToken
 *
 * @author christian
 */
class PHPToken {

    protected $name = '';
    protected $data = '';
    protected $info = '';
    protected $parentToken = null;
    protected $childs = array();

    public function __construct($name, $data, $info) {
        $this->name = $name;
        $this->info = $info;
        $this->data = $data;
        $this->parentToken = &$this;
    }

    public function getData() {
        return($this->data);
    }

    public function getName() {
        return($this->name);
    }

    public function getInfo() {
        return($this->info);
    }

    public function setParent(&$token) {
        $this->parentToken = $token;
    }

    public function getParent() {
        return($this->parentToken);
    }

    public function addChildToken(&$token) {
        $this->childs[] = $token;
    }

    public function getLastChild() {
        $max = count($this->childs);
        return($this->childs[$max - 1]);
    }

    public function getChilds() {
        return($this->childs);
    }

}
