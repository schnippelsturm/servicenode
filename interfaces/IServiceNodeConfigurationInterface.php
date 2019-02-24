<?php
namespace ServiceNode\Configuration;

interface IServiceNodeConfigurationInterface {
    
    public function loadConfiguration($filename);
         
    public function saveConfiguration($filename);
          
    public function isValid();
    
    
}



?>