<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Cookie;

use \PHPXI\Libraries\Config\Config as Config;

class Cookie
{
    
    private static $prefix = "phpxi_project_";
    private static $timeout = 3600;
    private static $cookie = [];
    private static $path = '/';
    private static $domain = '';
    private static $secure = false;


    public static function autoload()
    {
        self::$prefix = Config::get("cookie.prefix");
        self::$timeout = Config::get("cookie.timeout");
        
        foreach($_COOKIE as $key => $value){
            if(mb_substr($key, 0, strlen(self::$prefix)) == self::$prefix){
                $id = mb_substr($key, strlen(self::$prefix), strlen($key));
                self::$cookie[$id] = $value;
            }
        }
    }
    
    public static function timeout($time = 3600)
    {
        self::$timeout = $time;
    }

    public static function path($path = '/')
    {
        self::$path = $path;
        return $path;
    }

    public static function domain($domain = '')
    {
        self::$domain = $domain;
    }

    public static function secure($secure = false)
    {
        self::$secure = $secure;
    }

    public static function set($key, $value)
    {
        self::$cookie[$key] = $value;
        $time = self::$timeout + time();
        $id = self::$prefix . $key;
        setcookie($id, $value, $time, self::$path, self::$domain, self::$secure, true);
    }

    public static function get($key)
    {
        if(isset(self::$cookie[$key]) and self::$cookie[$key] != ""){
            return self::$cookie[$key];
        }else{
            return false;
        }
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
        unset(self::$cookie[$key]);
        $time = time() - 3600;
        $id = self::$prefix . $key;
        setcookie($id, null, $time);
    }

}
