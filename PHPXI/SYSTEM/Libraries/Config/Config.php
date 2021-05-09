<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Config;

use \PHPXI\Libraries\Base\Base as Base;

class Config
{

    public static function get($key, $type = false)
    {
        $ids = explode(".", $key);
        if (sizeof($ids) > 1) {
            if (Base::get($ids[0], "config")) {
                $return = Base::get($ids[0], "config");
                unset($ids[0]);
                foreach ($ids as $id) {
                    if (isset($return[$id])) {
                        $return = $return[$id];
                    } else {
                        $return = false;
                    }
                }
            }
        } else {
            if (Base::get($key, "config")) {
                $return = Base::get($key, "config");
            }
        }
        if (isset($return)) {
            if ($type and is_array($return)) {
                $return = arrayObject($return);
            }
        } else {
            $return = false;
        }
        return $return;
    }

    public static function set($key, $value)
    {
        $ids = explode(".", $key);
        if (sizeof($ids) == 1) {
            Base::set($key, $value, "config");
            return true;
        } elseif (sizeof($ids) == 2) {
            $configs = Base::get("", "config");
            $configs[$ids[0]][$ids[1]] = $value;
            Base::set("", $configs, "config");
            return true;
        } else {
            return false;
        }
    }

}
