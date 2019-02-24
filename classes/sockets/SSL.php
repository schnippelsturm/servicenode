<?php

namespace ServiceNode\sockets;

/**
 * Description of SSL
 *
 * @author christian
 */
class SSL {
    protected $openssl_conf;
    //put your code here

    public function getNewCSR($name) {
        $config = array('config' => $this->openssl_conf);
        $pkey = \openssl_pkey_new($config);
        $csr = \openssl_csr_new($name, $pkey, $config);
        return($csr);
    }

}
