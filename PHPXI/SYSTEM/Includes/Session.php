<?php
namespace PHPXI;

class Session{
    
    private array $session = [];
    
    function __construct(){
        $this->session = $_SESSION;
    }

    function __destruct(){
        if(sizeof($this->session) == 0){
            $this->destroy();
        }
    }
    
    public function destroy(){
        session_unset();
        session_destroy();
    }

    public function id(){
        return session_id();
    }

    public function item(string $key){
        if(isset($this->session[$key]) and $this->session[$key] != ""){
            return $this->session[$key];
        }else{
            return false;
        }
    }

    public function set(string $key, $value): void{
        $this->session[$key] = $value;
        $_SESSION[$key] = $value;
    }
    
    public function add(string $key, $value): void{
        $this->set($key, $value);
    }

    
    public function update(string $key, $value): void{
        $this->set($key, $value);
    }
    
    public function delete(string $key): void{
        $this->session[$key] = null;
        $_SESSION[$key] = null;
        unset($this->session[$key]);
        unset($_SESSION[$key]);
    }

}
