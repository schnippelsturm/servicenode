<?php

namespace ServiceNode\protocol\http;

/**
 * Description of HTTPStore
 *
 * @author christian
 */
class HTTPStore implements \ServiceNode\storage\IContainer {

    protected $document_root = '/';
    protected $fileStore = null;
    protected $protected_filesext = array('php', 'phps', 'phpt', 'phtml', 'inc');

    public function __construct($rootDir) {
        $this->document_root = \getcwd();
        if (\is_dir($rootDir)) {
            $this->document_root = $rootDir;
        }
        $this->fileStore = new \ServiceNode\storage\file\FileStore($this->document_root);
        \chdir($this->document_root);
        \set_include_path($this->document_root);
    }

    public function __destruct() {
        unset($this->fileStore);
    }

    protected function isProtectedFile($filename) {
        $extension = \pathinfo($filename, PATHINFO_EXTENSION);
        return(\in_array($extension, $this->protected_filesext));
    }

    public function getDocPath($url) {
        $path = \pathinfo($url, PATHINFO_DIRNAME);
        if ($path === false) {
            $path = null;
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, null);
        }
        if ($path != null) {
            $path = $this->document_root . \DIRECTORY_SEPARATOR . $path;
        } else {
            $path = $this->document_root;
        }
        return(\realpath($path));
    }

    public function getDefaultMimeType($url) {
        $path = $this->getDocPath($url);
        $filename = $path . \DIRECTORY_SEPARATOR . \basename($url);
        $mimetypeHandler = new \ServiceNode\mimetypes\MimeTypeBaseHandler();
        return($mimetypeHandler->getDefaultMimeTypeOfFile($filename));
    }

    protected function getFileContent($filename) {
        \chdir($this->document_root);
        $content = null;
        try {
            $extension = \pathinfo($filename, PATHINFO_EXTENSION);
            $content = $this->fileStore->read($filename);
        } catch (\ServiceNode\storage\file\FileAccessExceptionException $ex) {
            throw new HTTPException(HTTP_RETURNCODE_FORBIDDEN_403, 403, $ex);
        } catch (\ServiceNode\storage\MissingFileException $ex) {
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, $ex);
        } catch (\Exception $ex) {
            throw new HTTPException(HTTP_RETURNCODE_INTERNAL_SERVERERROR_500, 500, $ex);
        }
        return($content);
    }

    protected function setFileContent($filename, $content) {
        $result = false;
        try {
            $result = $this->fileStore->save($filename, $content);
        } catch (\Exception $ex) {
            $result = false;
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, $ex);
        }
        return($result);
    }

    protected function removeFile($filename) {
        $result = false;
        try {
            $result = $this->fileStore->delete($filename);
        } catch (\Exception $ex) {
            $result = false;
            throw new HTTPException(HTTP_RETURNCODE_NOT_FOUND_404, 404, $ex);
        }
        return($result);
    }

    protected function readDir($dirname) {
        $files = $this->fileStore->read($dirname);
        if (\is_array($files)) {
            $data = '<html><body>';
            foreach ($files as $key => $file) {
                if ($this->isProtectedFile($file) === false) {
                    $data.='<a href="' . $file . '" >' . \basename($file) . '</a> => (' . $file . ')</br>';
                }
            }
            $data.='</body></html>';
        }
        return($data);
    }

    public function readResource($url) {
        $path = $this->getDocPath($url);
        $filename = $path . \DIRECTORY_SEPARATOR . \basename($url);
        return($this->read($filename));
    }

    public function read($filename) {
        if (\is_dir($filename)) {
            return($this->readDir($filename));
        }
        return($this->getFileContent($filename));
    }

    public function save($filename, $value) {
        return($this->setFileContent($filename, $value));
    }

    public function delete($filename) {
        return($this->removeFile($filename));
    }

}

?>
