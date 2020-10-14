<?php
namespace PHPXI;

class Input{
    
    public $post;
    public $get;
    public $files;
    public $request;
    
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
    
    function post($key = ""){
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
    
    function get($key = ""){
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
    
    function files($files = ""){
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
    
    function request($key = ""){
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