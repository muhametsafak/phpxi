<?php
namespace PHPXI;

class Server{
    private array $server = [];

    function __construct(){
        $this->server = $_SERVER;
    }
    
    public function item(string $key){
        if(isset($this->server[$key]) and trim($this->server[$key]) != ""){
            return $this->server[$key];
        }else{
            return false;
        }
    }

    public function set(string $key, $value): void{
        $this->server[$key] = $value;
        $_SERVER[$key] = $value;
    }

}