<?php
namespace PHPXI\SYSTEM;

class Route{

    public $uri;
    
    public $controller;
    public $method;

    function __construct(){
        $this->uri = array();
        $uri = mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8");
        if($uri != ""){
          $this->uri = array_filter(explode('/', $uri));
        }
    }
    
    public function get($id){
        if(isset($this->uri[$id])){
            return $this->uri[$id];
        }else{
            return false;
        }
    }
    
}
