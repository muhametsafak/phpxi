<?php
namespace PHPXI;

class Input{
    
    public array $post = [];
    public array $get = [];
    public array $files = [];
    public array $request = [];
    
    function __construct(){
        if(isset($_POST)){
            $this->post = $_POST;
        }
        if(isset($_GET)){
            $this->get = $_GET;
        }
        if(isset($_FILES)){
            $this->files = $_FILES;
        }
        if(isset($_REQUEST)){
            $this->request = $_REQUEST;
        }
    }
    
    function post(string $key = ""){
        if($key == ""){
            return $this->post;
        }else{
            if(isset($this->post[$key])){
                if(!is_array($this->post[$key]) and trim($this->post[$key]) != ""){
                    return trim($this->post[$key]);
                }else{
                    return $this->post[$key];
                }
            }else{
                return false;
            }
        }
    }
    
    function get(string $key = ""){
        if($key == ""){
            return $this->get;
        }else{
            if(isset($this->get[$key]) and trim($this->get[$key]) != ""){
                return trim($this->get[$key]);
            }else{
                return false;
            }
        }
    }
    
    function files(string $files = ""){
        if($files == ""){
            return $this->files;
        }else{
            if(isset($this->files[$files]) and $this->files[$files] != ""){
                return $this->files[$files];
            }else{
                return false;
            }
        }
    }
    
    function request(string $key = ""){
        if($key == ""){
            return $this->request;
        }else{
            if(isset($this->request[$key]) and trim($this->request[$key]) != ""){
                return trim($this->request[$key]);
            }else{
                return false;
            }
        }
    }

}