<?php

namespace ServiceNode\Process;

/**
 * Description of MultiProcessController
 *
 * @author christian
 */
class MultiProcessController extends AbstractProcessController {

    const TOTAL_MAX_CHILDREN = 300;

    protected $servicename = 'servicenode';
    protected $bparent = false;
    protected $shutdownSignal = false;
    protected $maxchildren = 30;
    protected $children= array();
    protected $childsignals = array(\SIGCHLD => "SIGCHLD", \SIGCLD => "SIGCLD");
    protected $termsignals = array(\SIGINT => "SIGINT", \SIGTERM => "SIGTERM", \SIGHUP => "SIGHUP", \SIGQUIT => "SIGQUIT");
    protected $signals = array(\SIGCHLD => "SIGCHLD", \SIGCLD => "SIGCLD", \SIGINT => "SIGINT", \SIGTERM => "SIGTERM", \SIGHUP => "SIGHUP", \SIGQUIT => "SIGQUIT");
    protected $pidfile = '';
    protected $daemonize = false;
    protected $monitor = null;
    protected $filestore = null;

    public function __construct($servicename, $maxchildren = 10, $daemonize = false) {
        $this->servicename = $servicename;
        $this->maxchildren = $maxchildren;
        $this->daemonize = (bool) $daemonize;
        $this->monitor = new \ServiceNode\monitor\Memory();
        $this->filestore = new \ServiceNode\storage\file\FileStore(\sys_get_temp_dir());
        $this->init();
    }

    public function __destruct() {
        $this->freeResources();
        unset($this->monitor);
        unset($this->filestore);
    }

    protected function setSignalHandler($signals, $signalfunc) {
        foreach ($signals as $signal => $signalname) {
            \pcntl_signal($signal, array(&$this, $signalfunc), true);
            \trigger_error("set signalhandler for " . $signalname . \PHP_EOL,\E_USER_NOTICE);
        }
    }

    protected function init() {
        $this->children[\getmypid()] = array();
        \set_time_limit(0);
        \ob_implicit_flush();
        declare(ticks = 1);
        \pcntl_signal_dispatch();
        \pcntl_signal(\SIGCHLD, \SIG_IGN); /* ignore child */
    //    $this->daemonize();
        $this->setSignalHandler($this->childsignals, "handleSignalCLD");
        $this->setSignalHandler($this->termsignals, "handleSignals");
        $this->bparent = false;
        $this->daemonize = false;
    }

    protected function daemonize() {
        if ($this->daemonize == true) {
            $daemonizer = new Daemonizer(\getcwd(), \posix_geteuid(), \posix_getegid());
            $daemonizer->stayDaemon();
            unset($daemonizer);
        }
    }

    protected function freeResources() {
        if ($this->bparent === true) {
            $this->releasePIDFile();
        }
    }

    protected function waitForChildren($waitforexit = false) {
        $exitstatus = 0;
        $exitpid = 0;
        $option = \WNOHANG;
        if ($waitforexit === true) {
            $option = \WUNTRACED;
        }
        while (($exitpid = \pcntl_wait($exitstatus, $option)) > 0) {
            $sig = \pcntl_wexitstatus($exitstatus);
            \trigger_error("child $exitpid terminated with exit($sig) " . \PHP_EOL, E_USER_NOTICE);
            $this->removeChild($exitpid);
        }
    }

    protected function runParent($pid) {
        if ($this->bparent !== true) {
            $this->setPIDFile();
            $this->bparent = true;
        }
        \usleep(300);
        \pcntl_signal_dispatch();
        $this->addChild($pid);
        if (\count($this->getmyChildren()) >= $this->maxchildren) {
            \trigger_error("prefork limit " . $this->maxchildren . " reached " . \PHP_EOL, E_USER_NOTICE);
            \pcntl_signal_dispatch();
            $this->waitForChildren();
            // \usleep(10);
            // $this->getStatus();
        }
        \trigger_error("children " . \var_export($this->getmyChildren(), true) . " children " . \PHP_EOL, E_USER_NOTICE);
    }

    protected function runChild() {
        $this->init();
        \usleep(100);
        exit(0);
    }

    protected function dofork() {
        try {
            if ($this->shutdownSignal === true) {
                $this->halt();
                exit(0);
            }
            $pid = \pcntl_fork();
            if ($pid < 0) {
                $this->shutdownSignal = true;
                $this->halt();
                \trigger_error(" could not fork " . \count($this->children[\getmypid()]) . \PHP_EOL, E_USER_ERROR);
                exit(0);
            } else if ($pid == 0) {
                $client=clone $this;
                $client->runChild();
            } else {
                $this->runParent($pid);
            }
        } catch (Exception $e) {
            \trigger_error('Exception  : ' . $e->getMessage() . \PHP_EOL, E_USER_ERROR);
        }
    }

    protected function startWork() {
       if (\count($this->getmyChildren()) < $this->maxchildren) {
            $this->dofork();
            \pcntl_signal_dispatch();
        } 
         \pcntl_signal_dispatch();
         $this->waitForChildren(false);
    }

    public function halt() {
        \trigger_error("shutdown " . \getmypid() . \PHP_EOL, E_USER_NOTICE);
        \trigger_error("shut down children ." . \var_export($this->getmyChildren(), true) . " " . \SIGTERM . \PHP_EOL, E_USER_NOTICE);
        foreach ($this->getmyChildren() as $childpid) {
            \trigger_error("shutdown ." . $childpid . " " . \SIGTERM . \PHP_EOL, E_USER_NOTICE);
            \posix_kill($childpid, \SIGTERM);
            \trigger_error("sending SIGTERM to $childpid" . \PHP_EOL, E_USER_NOTICE);
        }
        $this->waitForChildren(true);
        $this->freeResources();
        exit(0);
    }

    public function handleSignalCLD($signo) {
        \trigger_error(\getmypid() . ": recieved signal via " . __CLASS__ . ":" . __METHOD__ . " " . $this->signals[$signo] . " ($signo)" . \PHP_EOL, E_USER_WARNING);
       // $this->waitForChildren(true);
        $this->stop();
    }

    public function handleSignals($signo) {
        \trigger_error(\getmypid().": recieved signal via " . __CLASS__ . ":" . __METHOD__ . " " . $this->signals[$signo] . " ($signo)" . \PHP_EOL, E_USER_WARNING);
        $this->shutdownSignal = true;
        $this->maxchilds = 0;
        $this->halt();
    }

    public function getPidFileName() {
        $filename = $this->servicename. '.pid';
        return(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $filename);
    }

    protected function setPIDFile() {
        $result = false;
        try {
            $filename = $this->getPidFileName();
            $result = $this->filestore->save($filename, \getmypid());
            \trigger_error("pid-file " . $filename . " stored. " . \PHP_EOL, E_USER_NOTICE);
        } catch (\Exception $ex) {
            $result = false;
        }
        if ($result == false) {
            \trigger_error("pid-file could not be stored !!" . \PHP_EOL, E_USER_ERROR);
        }
        return($result);
    }

    protected function releasePIDFile() {
        $result = true;
        try {
            $filename = $this->getPidFileName();
            if (\file_exists($filename)) {
                $result = $this->filestore->delete($filename);
                if ($result === true) {
                    \trigger_error("pid-file " . $filename . " released " . \PHP_EOL, \E_USER_WARNING);
                }
            }
        } catch (\Exception $ex) {
            \trigger_error(__CLASS__ . '::' . __METHOD__ . " Exception " . $ex->getMessage() . "\r\n", \E_USER_ERROR);
            $result = false;
        }
        if ($result == false) {
            \trigger_error("pid-file could not be released !!" . \PHP_EOL, \E_USER_WARNING);
        }
        return($result);
    }

    public function getStatus() {
        $pid = null;
        $filename = $this->getPidFileName();
        if (\file_exists($filename)) {
            $pid = (int) $this->filestore->read($filename);
            \trigger_error("service is running with PID $pid " . \PHP_EOL, \E_USER_NOTICE);
        }
        return($pid);
    }

    public function stop() {
        $this->maxchildren = 0;
        $this->shutdownSignal = true;
        // $this->waitForChildren(true);
        $pid = $this->getStatus();
        if ((!\is_null($pid)) && ($pid != \getmypid())) {
            \trigger_error("send SIGTERM to $pid ".\PHP_EOL,E_USER_NOTICE);
            \posix_kill($pid, \SIGTERM);
            \pcntl_signal_dispatch();
        }
        $this->halt();
    }

    protected function getApplicationLogFilename() {
        $name = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $this->servicename . '.log';
        return($name);
    }

    protected function getErrorLogFilename() {
        $name = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $this->servicename . '_error.log';
        return($name);
    }

    public function setMaxChildren($maxChildren) {
        if (\is_int($maxChildren) && ($maxChildren > 0)) {
            $this->maxchildren = \min($maxChildren, self::TOTAL_MAX_CHILDREN);
        }
    }

    public function getServiceName() {
        return($this->servicename);
    }

    protected function closeSTDFileHandles() {
        try {
            while (\ob_get_level() > 0) {
                @\ob_end_flush();
            }
        } catch (\Exception $ex) {
            \trigger_error(__CLASS__ . ":" . __METHOD__ . ':' . $ex->getMessage() . \PHP_EOL);
        }
    }
    
   
   protected function getmyChildren() {
       $result=array();
       if (\is_array($this->children[\getmypid()])) {
           $result=&$this->children[\getmypid()];
       }
       \reset($result);
       return($result);
   }
   
   protected  function addChild($pid) {
       $this->children[\getmypid()][]=$pid;
   }
   
   protected function removeChild($pid) {
       if (\in_array($pid, $this->children[\getmypid()])) {
          $this->children[\getmypid()]=\array_diff($this->children[\getmypid()],array($pid));
       }
   }
   
 }

?>
