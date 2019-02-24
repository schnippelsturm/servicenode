<?php

include_once('classes/ClassLoader.php');

$directories=array();
$directories[]=__DIR__.\DIRECTORY_SEPARATOR."interfaces";
$directories[]=__DIR__.\DIRECTORY_SEPARATOR."classes";
$directories[]=__DIR__.\DIRECTORY_SEPARATOR."applications";
$ob = new Classloader($directories);
\spl_autoload_register(array(&$ob, 'loadClasses'), true, false);
/*$directories=array();
$directories[]=__DIR__.\DIRECTORY_SEPARATOR."applications";
$ob-> */










?>
