<?php
namespace PHPXI\Libraries\Input;

class Input{
    
    private static $post = [];
    private static $get = [];
    private static $files = [];
    private static $request = [];
    
    public static function autoload(){
        if(isset($_GET)){
            self::$get = self::allClear($_GET);
        }
        if(isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] === "POST"){
            if(isset($_POST)){
                self::$post = self::allClear($_POST);
            }
            if(isset($_FILES)){
                self::$files = self::allClear($_FILES);
            }
        }
        if(isset($_REQUEST)){
            self::$request = self::allClear($_REQUEST);
        }
    }

    private static function clear($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private static function allClear($data = [])
    {
        $return = [];
        if(is_array($data) and sizeof($data) > 0){
            foreach($data as $key => $value){
                if(is_array($value)){
                    $return[$key] = self::allClear($value);
                }else{
                    $return[$key] = self::clear($value);
                }
            }
        }
    }
    
    public static function post($key = '')
    {
        if($key == ""){
            return self::$post;
        }else{
            if(isset(self::$post[$key])){
                return self::$post[$key];
            }else{
                return false;
            }
        }
    }
    
    public static function get($key = ""){
        if($key == ""){
            return self::$get;
        }else{
            if(isset(self::$get[$key]) and trim(self::$get[$key]) != ""){
                return self::$get[$key];
            }else{
                return false;
            }
        }
    }
    
    public static function files($files = ""){
        if($files == ""){
            return self::$files;
        }else{
            if(isset(self::$files[$files]) and self::$files[$files] != ""){
                return self::$files[$files];
            }else{
                return false;
            }
        }
    }
    
    public static function request($key = ""){
        if($key == ""){
            return self::$request;
        }else{
            if(isset(self::$request[$key]) and trim(self::$request[$key]) != ""){
                return self::$request[$key];
            }else{
                return false;
            }
        }
    }

}