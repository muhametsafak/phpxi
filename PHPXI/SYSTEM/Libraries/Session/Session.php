<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Session;

class Session{
    
    private static $session = [];
    
    public static function autoload()
    {
        self::$session = $_SESSION;
    }

    function __destruct()
    {
        if(sizeof(self::$session) == 0){
            self::unset();
            self::destroy();
        }
    }
    
    public static function unset()
    {
        session_unset();
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function id()
    {
        return session_id();
    }

    public static function get($key)
    {
        if(isset(self::$session[$key]) and self::$session[$key] != ""){
            return self::$session[$key];
        }else{
            return false;
        }
    }

    public static function set($key, $value)
    {
        self::$session[$key] = $value;
        $_SESSION[$key] = $value;
    }
    
    public static function add($key, $value)
    {
        self::set($key, $value);
    }

    
    public static function update($key, $value)
    {
        self::set($key, $value);
    }
    
    public static function delete($key)
    {
        self::$session[$key] = null;
        $_SESSION[$key] = null;
        unset(self::$session[$key]);
        unset($_SESSION[$key]);
    }

}
