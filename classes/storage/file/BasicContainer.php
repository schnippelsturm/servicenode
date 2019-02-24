<?php

namespace ServiceNode\storage\file;

/**
 * Description of Container
 *
 * @author christian
 */
class BasicContainer implements \ServiceNode\storage\IContainer {

    protected $symlinks_allowed = false;
    protected $root = null;

    public function __construct($path) {
        if (\file_exists($path) && \is_dir($path) && \is_link($path) == false) {
            $this->root = \realpath($path);
        }
    }

    protected function inroot($filename) {
        $result = \is_file($filename);
        if ($result === true) {
            $info = \pathinfo($filename);
            $path = \realpath($info["dirname"]);
            $result = (($result) && (\strpos($path, $this->root) !== false));
        }
        return($result);
    }

    protected function openFile($filename) {
        $handle = null;
        if (\file_exists($filename)) {
            $handle = \fopen($filename, "rb+");
        }
        return($handle);
    }

    protected function closeFile($handle) {
        if (!\is_null($handle)) {
            \fclose($handle);
            $handle = null;
        }
        return($handle);
    }

    protected function readFile($handle) {
        $data = null;
        if (\flock($handle, LOCK_SH)) {
            \rewind($handle);
            while (!\feof($handle)) {
                $data.=\fread($handle, 8192);
            }
        }
        \flock($handle, LOCK_UN);
        return($data);
    }

    protected function saveFile($handle, $value) {
        $result = false;
        if (\flock($handle, LOCK_EX)) {
            \rewind($handle);
            $result = \fwrite($handle, $value);
        }
        \flock($handle, LOCK_UN);
        return($result);
    }

    public function read($filename) {
        $data = null;
        if ($this->inroot($filename) === true) {
            $filehandle = $this->openFile($filename);
            $data = $this->readFile($filehandle);
            $this->closeFile($filehandle);
        } else {
            throw new AccessException("File not in Rootdirectory !!");
        }
        return($data);
    }

    public function save($filename, $value) {
        $data = null;
        if ($this->inroot($filename) === true) {
            $filehandle = $this->openFile($filename);
            $data = $this->saveFile($filehandle, $value);
            $this->closeFile($filehandle);
        } else {
            throw new AccessException("File not in Rootdirectory !!");
        }
        return($data);
    }

}
