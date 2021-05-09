<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Language;

use \PHPXI\Libraries\Config\Config as Config;
use \PHPXI\Libraries\Debugging\Logger as Logger;
use \PHPXI\Libraries\Base\Base as Base;

class Language
{

    public static function set($set)
    {
        Config::set("language.set", $set);
        self::load();
    }

    public static function get()
    {
        return Config::get("language.set");
    }

    public static function load()
    {
        $path = APPLICATION_PATH . "Languages/" . Config::get("language.set") . "/app.php";
        if (file_exists($path) || DEVELOPMENT) {
            $lang = array();
            require_once $path;
            Base::set("lang", $lang, "language");
        } else {
            self::set(Config::get("language.default"));
        }
    }

    private static function interpolate($message, array $context = array())
    {
        $replace = array();
        $i = 0;
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
                $replace['{' . $i . '}'] = $val;
                $i++;
            }
        }
        return strtr($message, $replace);
    }

    public static function r($key, $value = [])
    {
        $lang = Base::get("lang", "language");
        if (isset($lang[$key])) {
            $return = $lang[$key];
        } else {
            $return = $key;
        }
        if (sizeof($value) > 0) {
            $return = self::interpolate($return, $value);
        }
        return $return;
    }

    public static function e($key, $value = [])
    {
        echo self::r($key, $value);
    }

}
