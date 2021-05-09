<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Session;

use \PHPXI\Libraries\Base\Base as Base;

class Session
{

    public function __destruct()
    {
        if (sizeof(Base::$data['session']) == 0) {
            self::unset();
            self::destroy();
        }
    }

    function unset()
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
        return Base::get($key, "session");
    }

    public static function set($key, $value)
    {
        Base::set($key, $value, "session");
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
        self::set($key, null);
        unset(self::$session[$key]);
        unset($_SESSION[$key]);
    }

}
