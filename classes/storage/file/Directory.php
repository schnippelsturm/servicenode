<?php

namespace ServiceNode\storage\file;

class Directory {

    protected $document_root = '/';
    protected $files = array();
    protected $directories = array();

    public function __construct($documentRoot) {
        $this->document_root = \getcwd();
        if (\is_dir($documentRoot)) {
            $this->document_root = \realpath($documentRoot);
        }
        $this->read();
    }

    public function __destruct() {
        unset($this->files);
        unset($this->directories);
    }

    public function getFiles() {
        \reset($this->files);
        return($this->files);
    }

    public function getDirectories() {
        \reset($this->directories);
        return($this->directories);
    }

    public function read() {
        $this->files = array();
        $this->directories = array();
        $directory = \opendir($this->document_root);
        while (false !== ($entry = \readdir($directory))) {
            $fullpath = $this->document_root . \DIRECTORY_SEPARATOR . $entry;
            if (\is_link($fullpath)) {
                $fullpath = \realpath($fullpath);
            }
            if ((\is_dir($fullpath)) && (!\in_array($entry, $this->directories))) {
                $this->directories[$fullpath] = $entry;
            }
            if ((\is_file($fullpath)) && (!\is_dir($fullpath)) && (!\in_array($entry, $this->files))) {
                $this->files[$fullpath] = $entry;
            }
        }
        \closedir($directory);
        unset($directory);
    }

}
