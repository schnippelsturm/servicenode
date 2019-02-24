#!/usr/bin/php -c /etc/php_cli.ini
<?php

include_once(__DIR__ . \DIRECTORY_SEPARATOR . 'loader.php');
$events = array('start', 'status', 'stop', 'restart');

\openlog("ServiceNodeServerLog", LOG_PID | LOG_PERROR, LOG_LOCAL0);

$std=new ServiceNode\parameter\StdInputParameter($paramdefinition);
$event=$std->getInputParam($events);
//print "Event: ".$event." \r\n";
try {
    $addr = '0.0.0.0';
    $port = 8181;
    $document_root = \realpath(getcwd()) . DIRECTORY_SEPARATOR . 'applications/www';
    $protocol = new \ServiceNode\protocol\http\HTTPRequestRouter($document_root);
    $app=new ServiceNode\Application\www\ExampleAppl($document_root);
    $restapp=new ServiceNode\Application\www\RestApp($document_root);
    $protocol->addRoute('/index.php', $app,'GET');  
    $protocol->addRoute('/rest.php', $restapp); 
      $server = new \ServiceNode\TCPServer($addr, $port, $protocol);
    $result = $server->handleEvent($event);
     \flush();
    unset($server);
    unset($requestHandler);
} finally {
     \flush();
    \closelog();
     \flush();
}

\spl_autoload_unregister(array($ob, 'loadClasses'));
?>
