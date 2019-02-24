<?php


namespace ServiceNode\parameter;

/**
 * Description of InputParam
 *
 * @author christian
 */
class StdInputParameter extends \ServiceNode\parameter\Parameter {
    protected $script='';
    protected $arg=  [];

    
    public function __construct(&$paramdefinition) {
        parent::__construct($paramdefinition);
        $arg=$GLOBALS['argv'];
        $this->script=  \array_shift($arg);
        $this->arg=$arg;
    }



    public function getInputParam(&$events) {
        $event = null;
        try {
            if (\count($this->arg) !== 1) {
                throw new \Exception('usage: '.\basename($this->script)." ".\implode(' | ',$events)." found: ".  \var_export($this->arg,true));
            }
            if (!\in_array($this->arg[0], $events)) {
                throw new \Exception('usage : '.\basename($this->script)." ".\implode(' | ',$events)." found: ".  \var_export($this->arg,true));
            }
            $event = $this->arg[0];
        } catch (\Exception $ex) {
            \trigger_error($ex->getMessage() . \PHP_EOL, \E_USER_ERROR);
           // print $ex->getMessage() . \PHP_EOL;
            exit(0);
        }
        return($event);
    }

}
