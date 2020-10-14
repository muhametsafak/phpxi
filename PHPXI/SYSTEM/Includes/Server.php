<?php
namespace PHPXI;

class Server{
    private $server;

    function __construct(){
        $this->server = $_SERVER;
    }
    
    public function item($key){
        if(isset($this->server[$key]) and trim($this->server[$key]) != ""){
            return $this->server[$key];
        }else{
            return false;
        }
    }

    public function set($key, $value){
        $this->server[$key] = $value;
        $_SERVER[$key] = $value;
    }

}