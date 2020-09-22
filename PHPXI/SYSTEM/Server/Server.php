<?php
namespace PHPXI\SYSTEM;

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

}