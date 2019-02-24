<?php

namespace ServiceNode\Parser;

/**
 * Description of PHPParser
 *
 * @author christian
 */
class PHPParser {

    protected $declared_class = '';
    protected $declared_interface = '';
    protected $extended_class = '';
    protected $extended_interfaces = array();
    protected $namespace = '';
    protected $implementing_interfaces = array();

    protected function reset() {
        $this->declared_class = '';
        $this->extended_class = '';
        $this->declared_interface='';
        $this->extended_interfaces=array();
        $this->extended_interfaces=array();
        $this->implementing_interfaces = array();
    }

    public function parseFile($filename) {
        $this->reset();
        $tree = $this->buildTree($filename);
        $tokenlist = $tree->getChilds();
        $this->namespace = $this->findNamespace($tokenlist);
        $this->parseForInterface($tokenlist);
        $this->parseForClass($tokenlist);
    }

    protected function parseForInterface($tokenlist) {
        $this->declared_interface = $this->findInterface($tokenlist);
        if (empty($this->declared_interface) == false) {
            $this->extended_interfaces = $this->findExtendedInterfaces($tokenlist);
        }
    }

    protected function parseForClass($tokenlist) {
        $this->declared_class = $this->findClass($tokenlist);
        if (empty($this->declared_class) == false) {
            $this->extended_class = $this->findExtendedClass($tokenlist);
            $this->implementing_interfaces = $this->findImplementingInterfaces($tokenlist);
        }
    }
    
    public function getDeclaredInterface() {
        return($this->declared_interface);
    }

    public function getExtendedInterfaces() {
        return($this->extended_interfaces);
    }

    public function getDeclaredClass() {
        return($this->declared_class);
    }

    public function getExtendedClass() {
        return($this->extended_class);
    }

    public function getImplementingInfaces() {
        return($this->implementing_interfaces);
    }

    protected function getFileToken($filename) {
        if (\is_readable($filename)) {
            $source = \php_strip_whitespace($filename);
            return($this->getTokensWithNames(\token_get_all($source)));
        }
        return(null);
    }

    protected function getTokenData($token) {
        $result = null;
        if (\is_array($token)) {
            $token_name = \is_array($token) ? \token_name($token[0]) : $token;
            $token_data = \is_array($token) ? $token[1] : $token;
            $token_info = \is_array($token) ? \token_name($token[2]) : $token;
            $result = new PHPToken($token_name, $token_data, $token_info);
        } else if (\is_numeric($result)) {
            $result = new PHPToken(\token_name((int) $token), null, null);
        } else {
            $result = new PHPToken($token, null, null);
        }
        return($result);
    }

    protected function getTokensWithNames($tokenlist) {
        $result = array();
        foreach ($tokenlist as $value) {
            $token = $this->getTokenData($value);
            if (!\is_null($token)) {
                $result[] = $token;
            }
        }
        return($result);
    }

    protected function findDeclaration($roottokenlist, $declaration) {
        $namespaceNames = array('T_STRING', 'T_NS_SEPARATOR', ',');
        $decl_name = '';
        \reset($roottokenlist);
        $max_key = \count($roottokenlist);
        $classnametokenkey = $max_key;
        foreach ($roottokenlist as $key => $token) {
            if (\in_array($token->getName(), $declaration)) {
                $classnametokenkey = $key;
                continue;
            }
            if (($key > $classnametokenkey) && (\in_array($token->getName(), $namespaceNames))) {
                $decl_name.=$token->getData();
            } else {
                $classnametokenkey = $max_key;
            }
        }
        return(\trim($decl_name));
    }

    protected function findInterface($roottokenlist) {
        $types = array('T_INTERFACE');
        $interface=$this->findDeclaration($roottokenlist, $types);
        return($this->getInterface($this->namespace, $interface));
    }

    protected function findClass($roottokenlist) {
        $types = array('T_CLASS');
        $class=$this->findDeclaration($roottokenlist, $types);
        return($this->getClass($this->namespace, $class));
    }

    protected function findNamespace($roottokenlist) {
        $types = array('T_NAMESPACE');
        return($this->findDeclaration($roottokenlist, $types));
    }

    protected function findExtendedClass($roottokenlist) {
        $types = array('T_EXTENDS');
        $classname=$this->findDeclaration($roottokenlist, $types);
        return($this->getClass($this->namespace, $classname));
    }

    protected function findExtendedInterfaces($roottokenlist) {
        $types = array('T_EXTENDS');
        $interfaces=array();
        $extended = \explode(',', $this->findDeclaration($roottokenlist, $types));
        foreach ($extended as $interface) {
          if (empty($interface)==false) {  
            $interfaces[]=$this->getInterface($this->namespace,$interface);  
          } 
        }
       return(\array_values($interfaces));
    }

    protected function findImplementingInterfaces($roottokenlist) {
        $types = array('T_IMPLEMENTS');
        $interfaces=array();
        $implements = \explode(',', $this->findDeclaration($roottokenlist, $types));
        foreach ($implements as $interface) {
          if (empty($interface)==false) {  
            $interfaces[]=$this->getInterface($this->namespace,$interface);  
          } 
        }
        return(\array_values($interfaces));
    }

    protected function buildTree($filename) {
        $tokenlist = $this->getFileToken($filename);
        $globalScopeToken = new PHPToken('GLOBAL_SCOPE', null, null);
        $aktScope = &$globalScopeToken;
        $aktScope->setParent($aktScope);
        \reset($tokenlist);
        foreach ($tokenlist as $token) {
            $name = $token->getName();
            if (($name == 'T_WHITESPACE') || ($name == 'T_CLOSE_TAG') || ($name == 'T_OPEN_TAG')) {
                continue;
            }
            if ($token->getName() == '}') {
                $aktScope = $aktScope->getParent();
            }
            $token->setParent($aktScope);
            $aktScope->addChildToken($token);
            if ($token->getName() == '{') {
                $aktScope = $aktScope->getLastChild();
            }
        }
        return($globalScopeToken);
    }

    protected function getClass($namespace, $classname) {
        if (($this->isAbsolutNamespace($classname)) || (empty($classname))) {
            return(\trim($classname,'\\'));
        }
        $classname = $namespace . '\\' . $classname;
        return(\trim($classname, '\\'));
    }

    protected function getInterface($namespace, $interfacename) {
        if (($this->isAbsolutNamespace($interfacename)) || (empty($interfacename))) {
            return(\trim($interfacename,'\\'));
        }
        $interfacename = $namespace . '\\' . $interfacename;
        return(\trim($interfacename, '\\'));
    }

    protected function isAbsolutNamespace($classname) {
        $name = \trim($classname);
        if (empty($name)) {
         return(false);
        }
        return($name[0] == "\\");
    }

    protected function rmEmptyValues($values) {
        $result = array();
        foreach ($values as $key => $value) {
            if ((!\is_null($value)) && ($value != '')) {
                $result[$key] = $value;
            }
        }
        return($result);
    }

    protected function getClassNameSpace($classname) {
        $values = $this->rmEmptyValues(\explode("\\", $classname));
        \array_pop($values);
        $namespace = \implode("\\", $values);
        if ($this->isAbsolutNamespace($classname)) {
            $namespace = '\\' . $namespace;
        }
        return($namespace);
    }

}
