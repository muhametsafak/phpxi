<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Server;

class Server{
    private static $server = [];

    public static function autoload()
    {
        self::$server = $_SERVER;
    }
    
    public static function get($key)
    {
        if(isset(self::$server[$key]) and trim(self::$server[$key]) != ""){
            return self::$server[$key];
        }else{
            return false;
        }
    }

    public static function set($key, $value)
    {
        self::$server[$key] = $value;
        $_SERVER[$key] = $value;
    }

}