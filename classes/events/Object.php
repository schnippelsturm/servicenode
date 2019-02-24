<?php

namespace  ServiceNode\events;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of method
 *
 * @author christian
 */
class EObject {
    //put your code here
    
   public function handleEvent(&$event) {
       if ($event instanceof Event) {
           $this->call($event->getMethod(),$event->getParams());
       }
    } 
   
   protected function call($method_name,$params=null) {
       if (\method_exists($this, $method_name)) {
           return \call_user_method_array($method_name, $this, $params);
       }
   }  
   
   
    
}
