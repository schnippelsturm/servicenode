<?php

namespace ServiceNode\storage\file;

/**
 *
 * @author christian
 */
class FileStore implements \ServiceNode\storage\IContainer {

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
            $result = (\is_dir($path) && (\strpos($path, $this->root) !== false));
        } elseif (\is_dir($filename)) {
            $path = \realpath($filename);
            $result = (\is_dir($path) && (\strpos($path, $this->root) !== false));
        }
        return($result);
    }

    protected function openFile($filename, $mode = "rb") {
        $handle = null;
        if (\file_exists($filename) || $mode !== "rb") {
            $handle = \fopen($filename, $mode);
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
        if (!is_resource($handle)) {
            return($data);
        }
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

    protected function appendToFile($handle, $value) {
        $result = false;
        if (\flock($handle, LOCK_EX)) {
            \fseek($handle, 0, SEEK_END);
            $result = \fwrite($handle, $value);
        }
        \flock($handle, LOCK_UN);
        return($result);
    }

    protected function isWritable($filename) {
        $result = false;
        if (\file_exists($filename)) {
            $result = ($this->inroot($filename) && (\is_file($filename)) && (\is_writable($filename)));
        } else {
            $dirname = \dirname($filename);
            $result = ($this->inroot($dirname) && (\is_dir($dirname)) && (\is_writable($dirname)));
        }
        return($result);
    }

    protected function getRelativePath($dirname) {
        $pattern = '/' . \preg_quote($this->root, DIRECTORY_SEPARATOR) . '/s';
        $path = \preg_replace($pattern, '', $dirname);
        if ($path == \DIRECTORY_SEPARATOR) {
            $path = '';
        }
        return($path);
    }

    protected function readDir($dirname) {
        $files = array();
        $dir = new \ServiceNode\storage\file\Directory($dirname);
        $path = $this->getRelativePath($dirname);
        $fullpath = $this->root.\DIRECTORY_SEPARATOR .$path;
        foreach ($dir->getfiles() as $key => $file) {
            $files[$key] = $path . \DIRECTORY_SEPARATOR . $file;
        }
        foreach ($dir->getDirectories() as $key => $file) {
            if ($this->inroot($fullpath . \DIRECTORY_SEPARATOR . $file)) {
                $files[$key] = $path . \DIRECTORY_SEPARATOR . $file;
           }
        }
        unset($dir);
        return($files);
    }

    public function read($filename) {
        $data = null;
        if (($this->inroot($filename) === true) && (\is_readable($filename) === true)) {
            if (\is_file($filename)) {
                $filehandle = $this->openFile($filename);
                $data = $this->readFile($filehandle);
                $this->closeFile($filehandle);
            } elseif (\is_dir($filename)) {
                $data = $this->readDir($filename);
            }
        } else {
            if (\file_exists($filename)) {
                throw new FileAccessException("access not allowed :" . $filename);
            } else {
                throw new \ServiceNode\storage\MissingFileException("file not found :" . $filename);
            }
        }
        return($data);
    }

    public function save($filename, $value) {
        $data = null;
        if (($this->isWritable($filename) === true)) {
            $filehandle = $this->openFile($filename, "cb");
            $data = $this->saveFile($filehandle, $value);
            $this->closeFile($filehandle);
        } else {
            throw new FileAccessException("saveFile not allowed");
        }
        return($data);
    }

    public function add($filename, $value) {
        $data = null;
        if (($this->isWritable($filename) === true)) {
            $filehandle = $this->openFile($filename, "cb");
            $data = $this->appendToFile($filehandle, $value);
            $this->closeFile($filehandle);
        } else {
            throw new FileAccessException("saveFile not allowed");
        }
        return($data);
    }

    public function copyFromStream($filename, &$inputstream) {
        $bytes = 0;
        if (($this->isWritable($filename) === true) && (\is_resource($inputstream))) {
            $filehandle = $this->openFile($filename, "cb");
            $bytes = \stream_copy_to_stream($inputstream, $filehandle);
            $this->closeFile($filehandle);
        } else {
            throw new FileAccessException(__METHOD__ . " not allowed");
        }
        return($bytes);
    }

    public function copyToStream($filename, &$outputstream) {
        $bytes = 0;
        $lock_support = \stream_supports_lock($outputstream);
        if (($this->inroot($filename) === true) && (\is_resource($outputstream)) && (\is_readable($filename) === true)) {
            $filehandle = $this->openFile($filename);
            if ($lock_support) {
                \flock($outputstream, \LOCK_EX);
            }
            $bytes = \stream_copy_to_stream($filehandle, $outputstream);
            $this->closeFile($filehandle);
            if ($lock_support) {
                \flock($outputstream, \LOCK_UN);
            }
        } else {
            throw new FileAccessException("not allowed");
        }
        return($bytes);
    }

    public function delete($filename) {
        $unlink = false;
        if ((\file_exists($filename) && ($this->isWritable($filename) === true))) {
            $filehandle = $this->openFile($filename, "cb");
            \flock($filehandle, LOCK_EX);
            \flock($filehandle, LOCK_UN);
            $this->closeFile($filehandle);
            $unlink = \unlink($filename);
        } else {
            throw new FileAccessException("delete not allowed");
        }
        return($unlink);
    }

}
