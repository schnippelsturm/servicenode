<?php



namespace ServiceNode\protocol\http;

/**
 * Description of HTTPCookie
 *
 * @author christian
 */
class HTTPCookie {
    protected  $name;
    protected  $value;
    protected  $maxage;
    protected  $expires;
    protected  $path='/';
    protected  $domain;
    protected  $secure=false;
    protected  $httpOnly=true;
    
    public function __construct($name,$value,$maxage,$domain=null,$path='/',$secure=false,$httpOnly=true) {
        $this->name=$name;
        $this->value=$value;
        $this->maxage=$maxage;
        $this->domain=$domain;
        $this->expires=  date("r", time()+$maxage);
        $this->path=$path;
        $this->secure=(bool)$secure;
        $this->httpOnly=(bool)$httpOnly;
    }


   protected function buildCookie() {
       $result=array();
       $result[]=' '.$this->name.'='.\urlencode($this->value); 
       $result[]='max-age='.$this->maxage;
       $result[]='expires='.$this->expires;
       $result[]='path='.$this->path;
       $result[]='domain='.$this->domain;
       $result[]='path='.$this->path;
       if ($this->secure) {
           $result[]='secure';
       }
       if ($this->httpOnly) {
           $result='httpOnly';
       }
       return(implode(' ;', $result));
   }  
   
   protected function readCookie($cookieStr) {
      $value_pairs=explode(';',$cookieStr);
      foreach ($value_pairs as $pair) {
          list($name,$value)=explode('=',trim($pair));
          if (\property_exists($this, $name)) {
              $this->$name=trim($value);
          }
      }
   }
   
    //put your code here
}
