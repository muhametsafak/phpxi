<?php
namespace PHPXI;

class Cookie{
    private $prefix = "phpxi_project_";
    private $timeout = 3600;
    private $cookie = array();

    function __construct(){
        global $config;
        if($config->item("cookie.prefix")){
            $this->prefix = $config->item("cookie.prefix");
        }
        if($config->item("cookie.timeout")){
            $this->timeout = $config->item("cookie.timeout");
        }
        foreach($_COOKIE as $key => $value){
            if(mb_substr($key, 0, strlen($this->prefix)) == $this->prefix){
                $id = mb_substr($key, strlen($this->prefix), strlen($key));
                $this->cookie[$id] = $value;
            }
        }
    }
    
    public function set($key, $value){
        $this->cookie[$key] = $value;
        $time = $this->timeout + time();
        $id = $this->prefix . $key;
        setcookie($id, $value, $time);
    }

    public function get($key){
        if(isset($this->cookie[$key]) and $this->cookie[$key] != ""){
            return $this->cookie[$key];
        }else{
            return false;
        }
    }

    public function add($key, $value){
        $this->set($key, $value);
    }

    public function update($key, $value){
        $this->set($key, $value);
    }

    public function delete($key){
        unset($this->cookie[$key]);
        $time = time() - 3600;
        $id = $this->prefix . $key;
        setcookie($id, null, $time);
    }

    public function item($key){
        return $this->get($key);
    }

}
