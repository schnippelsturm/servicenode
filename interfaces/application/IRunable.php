<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ServiceNode\Application;

/**
 *
 * @author christian
 */
interface IRunableInterface {
    //put your code here
    
    public function call($request=null);
    
    
}
