<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Cookie;

use \PHPXI\Libraries\Config\Config as Config;
use \PHPXI\Libraries\Base\Base as Base;

class Cookie
{

    private static $prefix = null;
    private static $timeout = null;
    private static $cookie = [];
    private static $path = '/';
    private static $domain = '';
    private static $secure = false;

    public static function timeout($time = 3600)
    {
        self::$timeout = $time;
        return new self();
    }

    public static function path($path = '/')
    {
        self::$path = $path;
        return new self();
    }

    public static function domain($domain = '')
    {
        self::$domain = $domain;
        return new self();
    }

    public static function secure($secure = false)
    {
        self::$secure = $secure;
        return new self();
    }

    public static function set($key, $value)
    {
        if(is_null(self::$prefix)){
            self::$prefix = Config::get("cookie.prefix");
        }
        if(is_null(self::$timeout)){
            self::$timeout =Config::get("cookie.timeout");
        }
        Base::set($key, $value, "cookie");
        $time = self::$timeout + time();
        $id = self::$prefix . $key;
        setcookie($id, $value, $time, self::$path, self::$domain, self::$secure, true);
        return new self();
    }

    public static function get($key)
    {
        return Base::get($key, "cookie");
    }

    public static function add($key, $value)
    {
        return self::set($key, $value);
    }

    public static function update($key, $value)
    {
        return self::set($key, $value);
    }

    public static function delete($key)
    {
        self::set($key, null);
        $time = time() - 3600;
        $id = self::$prefix . $key;
        setcookie($id, null, $time);
    }

}
