<?php

namespace ServiceNode\Process;

class Daemonizer {

    //const SERVICENODE_USER='servicenode'; 
    //const SERVICENODE_GRP='servicenode'; 

    protected $workingDir = '/';
    protected $uid = 0;
    protected $gid = 0;
    protected $childsignals = array(\SIGCHLD => "SIGCHLD", \SIGCLD => "SIGCLD");
    protected $tremsignals = array(\SIGINT => "SIGINT", \SIGTERM => "SIGTERM", \SIGHUP => "SIGHUP", \SIGQUIT => "SIGQUIT");
    protected $signals = array(\SIGTTOU => "SIGTTOU", \SIGTTIN => "SIGTTIN", \SIGTSTP => "SIGTSTP", \SIGCHLD => "SIGCHLD", \SIGCLD => "SIGCLD", \SIGINT => "SIGINT", \SIGTERM => "SIGTERM", \SIGHUP => "SIGHUP", \SIGQUIT => "SIGQUIT");
    protected $deactsignals = array(\SIGTTOU => "SIGTTOU", \SIGTTIN => "SIGTTIN", \SIGTSTP => "SIGTSTP");

    public function __construct($workingDir, $uid, $gid) {
        $this->workingDir = \getcwd();
        if (\is_dir($workingDir) && \is_readable($workingDir)) {
            $this->workingDir = $workingDir;
        }        
        $this->uid = \posix_geteuid();
        $this->gid = \posix_getegid();
        if ($this->uid == 0) {
            $this->uid = $uid;
            $this->gid = $gid;
        }
    }

    protected function deactivate_Signals() {
        foreach ($this->deactsignals as $signalk => $signal) {
            \pcntl_signal($signalk, \SIG_IGN);
        }
    }

    protected function fork() {
        try {
            $pcntl_fork = \pcntl_fork();
            if ($pcntl_fork > 0) {
                \usleep(10);
                exit(0);
            }
        } catch (Exception $ex) {
            \syslog($ex);
        }
    }

    protected function changeSession() {
        $posix_setsid = \posix_setsid();
        return($posix_setsid > 0);
    }

    protected function changeDirectory() {
        $chdir = \chdir($this->workingDir);
        return($chdir);
    }

    protected function changeUser() {
        return(\posix_setuid($this->uid) && \posix_setgid($this->gid));
    }

    public function stayDaemon() {
        $this->closeSTDFileHandles();
        $this->fork();
        $this->closeSTDFileHandles();
        //$this->changeSession();
       $this->deactivate_Signals();
        \fclose(STDIN);
        \fclose(STDOUT);
        \fclose(STDERR);
        $stdIn = \fopen('/dev/null', 'r'); // set fd/0
        $stdOut = \fopen('/dev/null', 'w'); // set fd/1
        $stdErr = \fopen('/dev/null', 'w'); // set fd/2
        $this->changeSession();
        $this->deactivate_Signals();
        $this->changeDirectory();
        $this->changeUser();
        \umask(027);
    }

    protected function closeSTDFileHandles() {
        try {
            while (\ob_get_level()>0) {
               @\ob_end_flush(); 
            }
        } catch (\Exception $ex) {
            \trigger_error(__CLASS__ . ":" . __METHOD__ . ':' . $ex->getMessage() . \PHP_EOL);
        }
    }

}

?>
