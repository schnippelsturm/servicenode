<?php

namespace ServiceNode\storage\file;


/**
 * Description of LogFileStore
 *
 * @author christian 
 */                            
class LogFileStore  implements \ServiceNode\storage\IContainer {
    protected $filestream=null;
  

    public function __construct($filename) {
       if ($this->iswritable($filename)) {
            $this->filestream = \fopen($filename, 'ab');
            \stream_set_write_buffer($this->filestream, 24576);
            \stream_set_blocking($this->filestream, false);
            \flock($this->filestream, LOCK_EX);
        } 
    }
    
    public function __destruct() {
        if (\is_resource($this->filestream)) {
        \flock($this->filestream,LOCK_UN);
        \fclose($this->filestream);
        unset($this->filestream);
        } 
    }

    public function read($filename) {
        
    }

    public function save($filename, $value) {
        
    }

     public function add($value) {
        $result=false;
        try {
            if (\is_resource($this->filestream)) {
                $result = \fwrite($this->filestream, $value);
            }
        } catch (Exception $ex) {
            $result=false;
        }
        return($result);
    } 

    protected function iswritable($filename) {
        $result = false;
        if (\file_exists($filename)) {
            $result = \is_file($filename) && \is_writable($filename);
        } else {
            $dir = \dirname($filename);
            $result = \is_dir($dir) && \is_writable($dir);
        }
        return($result);
    } 
    
    
    

}
