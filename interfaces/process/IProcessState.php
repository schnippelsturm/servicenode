<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\Process;

/**
 *
 * @author christian
 */
interface IProcessState {
    //put your code here
    public function doAction($context);
    public function getState(); 
    public function getStateInfo();     
    
}
