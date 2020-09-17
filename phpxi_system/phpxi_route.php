<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}

class PHPXI_ROUTE{

    public $uri;
    
    public $controller;
    public $method;

    function __construct(){
        $this->uri = array();
        if(isset($_GET['uri']) and trim($_GET['uri']) != ""){
            $this->uri = array_filter(explode('/', $_GET['uri']));
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
