<?php
namespace PHPXI\SYSTEM;

session_start();

class Session{
    
    public $session;
    
    function __construct(){
        $this->session = $_SESSION;
    }
    
    public function destroy(){
        session_destroy();
    }

    public function id(){
        return session_id();
    }

    public function item($key){
        if(isset($this->session[$key]) and $this->session[$key] != ""){
            return $this->session[$key];
        }else{
            return false;
        }
    }

    public function set($key, $value){
        $this->session[$key] = $value;
        $_SESSION[$key] = $value;
    }
    
    public function add($key, $value){
        $this->set($key, $value);
    }

    
    public function update($key, $value){
        $this->set($key, $value);
    }
    
    public function delete($key){
        $this->session[$key] = null;
        $_SESSION[$key] = null;
        unset($this->session[$key]);
        unset($_SESSION[$key]);
    }

}
