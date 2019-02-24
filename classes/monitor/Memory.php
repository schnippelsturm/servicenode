<?php

namespace ServiceNode\monitor;

if (!defined('BYTE_LONG')) {
    define('BYTE_LONG', 'B');
}
if (!defined('BYTE_SHORT')) {
    define('BYTE_SHORT', 'B');
}
if (!defined('KILOBYTE_LONG')) {
    define('KILOBYTE_LONG', 'KB');
}
if (!defined('KILOBYTE_SHORT')) {
    define('KILOBYTE_SHORT', 'K');
}
if (!defined('MEGABYTE_LONG')) {
    define('MEGABYTE_LONG', 'MB');
}
if (!defined('MEGABYTE_SHORT')) {
    define('MEGABYTE_SHORT', 'M');
}
if (!defined('GIGABYTE_LONG')) {
    define('GIGABYTE_LONG', 'GB');
}
if (!defined('GIGABYTE_SHORT')) {
    define('GIGABYTE_SHORT', 'G');
}

/**
 * Description of Memory
 *
 * @author christian
 */
class Memory {

    protected $byte_exp = array();
    protected $exp = 1;

    //put your code here

    public function __construct($unit = '') {
        $this->init();
        $this->exp = $this->unitExp($unit);
    }

    protected function init() {
        $this->byte_exp[BYTE_LONG] = 0;
        $this->byte_exp[BYTE_SHORT] = 0;
        $this->byte_exp[KILOBYTE_LONG] = 1;
        $this->byte_exp[KILOBYTE_SHORT] = 1;
        $this->byte_exp[MEGABYTE_LONG] = 2;
        $this->byte_exp[MEGABYTE_SHORT] = 2;
        $this->byte_exp[GIGABYTE_LONG] = 3;
        $this->byte_exp[GIGABYTE_SHORT] = 3;
    }

    protected function unitExp($unit) {
        $exp = 0;
        if (\key_exists($unit, $this->byte_exp)) {
            $exp = (int) $this->byte_exp[$unit];
        }
        return(\pow(1024, $exp));
    }

    protected function getBytes($confvalue) {
        $value = 0;
        $mult = 1;
        $pattern = '/^(\d+)\s*([^\d]*)$/';
        $matches = array();
        if (\preg_match($pattern, $confvalue, $matches)) {
            $value = (int) $matches[1];
            if (isset($matches[2])) {
                $unit = \strtoupper(\trim($matches[2]));
                $mult = $this->unitExp($unit);
            }
        }
        return($value * $mult);
    }

    public function getUsedMemory() {
        return(\memory_get_usage(true) / $this->exp);
    }

    public function getUsedPeakMemory() {
        return(\memory_get_peak_usage(true) / $this->exp);
    }

    public function getTotalMemory() {
        $value = 0;
        $limit = \ini_get('memory_limit');
        if (\gettype($limit) == 'string') {
            $value = $this->getBytes($limit);
        }
        return($value / $this->exp);
    }

    public function getRelativeMemoryUsage() {
        return($this->getUsedMemory() / $this->getTotalMemory());
    }

    public function getRelativePeakMemoryUsage() {
        return($this->getUsedPeakMemory() / $this->getTotalMemory());
    }

}

?>
