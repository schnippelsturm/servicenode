<?php

include_once('classes/parser/PHPToken.php');
include_once('classes/parser/PHPParser.php');

class Classloader {

    private $classfile_extentions = array('inc', 'php');
    private $load = array();
    protected $pathes = array();
    protected $files = array();
    protected $directories = array();
    protected $document_root = '.';
    protected $path;
    protected $declared_classes = array();
    protected $declared_interfaces = array();
    protected $file_classes = array();
    protected $file_interfaces = array();
    protected $dependencies = array();

    public function __construct($classDirectories) {
        $this->declared_classes = \get_declared_classes();
        $this->declared_interfaces = \get_declared_interfaces();
        $this->init($classDirectories);
    }

    protected function init($classDirectories) {
        if (\is_array($classDirectories)) {
            foreach ($classDirectories as $documentRoot) {
                $this->document_root = '';
                if (\is_dir($documentRoot) && \is_readable($documentRoot)) {
                    $this->document_root = \realpath($documentRoot);
                    $this->getAllClassFiles();
                }
            }
        }
        $this->parseFiles(); 
    }

    protected function inroot($document_root, $filename) {
        $result = \is_file($filename);
        if ($result === true) {
            $info = \pathinfo($filename);
            $path = \realpath($info["dirname"]);
            $result = (\is_dir($path) && (\strpos($path, $document_root) !== false));
        } elseif (\is_dir($filename)) {
            $path = \realpath($filename);
            $result = (\is_dir($path) && (\strpos($path, $document_root) !== false));
        }
        return($result);
    }

    public function getDeclaredClasses() {
        return($this->declared_classes);
    }

    public function getDeclaredInterfaces() {
        return($this->declared_interfaces);
    }

    public function __destruct() {
        $this->unregister_loader();
        unset($this->files);
        unset($this->directories);
    }

    public function getFiles() {
        \reset($this->files);
        return($this->files);
    }
    
    public function getDependencies() {
        \reset($this->dependencies);
        return($this->dependencies);
    }

    public function getLoad() {
        \reset($this->load);
        return($this->load);
    }

    public function getDirectories() {
        \reset($this->directories);
        return($this->directories);
    }

    protected function read($document_root) {
        $directory = \opendir($document_root);
        while (false !== ($entry = \readdir($directory))) {
            $fullpath = $document_root . DIRECTORY_SEPARATOR . $entry;
            if (\is_link($fullpath) || \is_dir($fullpath)) {
                $fullpath = \realpath($fullpath);
            }
            if ($this->inroot($this->document_root, $fullpath) == false) {
                continue;
            }
            if ($this->isClassFile($fullpath) && (!\in_array($fullpath, $this->files))) {
                $this->files[$fullpath] = $fullpath;
            }
            if ((\is_dir($fullpath)) && ($this->inroot($document_root, $fullpath)) && (!\in_array($fullpath, $this->directories))) {
                $this->directories[$fullpath] = $fullpath;
                $this->read($fullpath);
            }
        }
        \closedir($directory);
        unset($directory);
    }

    protected function isClassFile($file) {
        $fileAllowed = ((\is_file($file)) && ($this->inroot($this->document_root, $file)) && (!\is_dir($file)) && \is_readable($file));
        $extension = \pathinfo($file, \PATHINFO_EXTENSION);
        $result = (($fileAllowed) && (\in_array($extension, $this->classfile_extentions)));
        return($result);
    }

    protected function getAllClassFiles() {
        $this->read($this->document_root);
    }

    public function loadClasses() {
        reset($this->files);
        foreach ($this->files as $file) {
            include_once($file);
        }
    }

    protected function registerInterface($interface, $file) {
        if ((empty($interface) == false) &&
                (!\key_exists($interface, $this->file_interfaces)) &&
                (!\key_exists($interface, $this->declared_interfaces))) {
            $this->file_interfaces[$interface] = $file;
        }

    }

    protected function registerClass($class, $file) {
        if ((empty($class) == false) &&
                (!\key_exists($class, $this->file_classes)) &&
                (!\key_exists($class, $this->declared_classes))) {
            $this->file_classes[$class] = $file;
        }
    }

    protected function registerDependency($depending, $depenceOn) {
        if ((empty($depenceOn) == false) && (empty($depending) == false)) {
            if (!\key_exists($depending, $this->dependencies)) {
                $this->dependencies[$depending] = array($depenceOn);
            } else {
                $this->dependencies[$depending][] = $depenceOn;
            }
        }
    }

    protected function parseFiles() {
        $parser = new \ServiceNode\Parser\PHPParser();
        reset($this->files);
        foreach ($this->files as $file) {
            $parser->parseFile($file);
            $interface = $parser->getDeclaredInterface();
            $this->registerInterface($interface, $file);
            $class = $parser->getDeclaredClass();
            $this->registerClass($class, $file);
            foreach ($parser->getExtendedInterfaces() as $value) {
                $this->registerDependency($interface, $value);
            }
            foreach ($parser->getImplementingInfaces() as $value) {
                $this->registerDependency($class, $value);
            }
            $this->registerDependency($class, $parser->getExtendedClass());
        }
        $this->orderLoad();
    }

    protected function orderDependencyLoad($artefakt) {
        if (\key_exists($artefakt, $this->dependencies)) {
            $dependencies = $this->dependencies[$artefakt];
            foreach ($dependencies as $value) {
                if ((!\key_exists($value, $this->load)) &&
                        (!\key_exists($value, $this->declared_interfaces)) &&
                        (!\key_exists($value, $this->declared_classes))) {
                    $this->orderDependencyLoad($value);
                }
            }
        }
        $this->load[] = $artefakt;
    }

    protected function orderLoad() {
        $this->load = [];
        reset($this->file_interfaces);
        foreach ($this->file_interfaces as $interface => $file) {
            $this->orderDependencyLoad($interface);
        }
        reset($this->file_classes);
        foreach ($this->file_classes as $class => $file) {
            $this->orderDependencyLoad($class);
        }
        unset($this->files);
        $this->files = array();
        reset($this->load);
        foreach ($this->load as $key => $value) {
            if (\key_exists($value, $this->file_interfaces)) {
                $this->files[$key] = $this->file_interfaces[$value];
            }
            if (\key_exists($value, $this->file_classes)) {
                $this->files[$key] = $this->file_classes[$value];
            }
        }
        \ksort($this->files);  
    }

    public function register_loader() {
        \spl_autoload_register(array(&$this, 'loadClasses'), true, false);
    }
      
    public function unregister_loader() {
        \spl_autoload_unregister(array(&$this, 'loadClasses'));
    }
    

    
}

?>
