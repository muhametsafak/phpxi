<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Input;

use \PHPXI\Libraries\Base\Base as Base;

class Input
{

    public static function post($key = '')
    {
        $post = Base::get("POST", "input");
        if ($key == "") {
            return $post;
        } else {
            if (isset($post[$key])) {
                return $post[$key];
            } else {
                return false;
            }
        }
    }

    public static function get($key = "")
    {
        $get = Base::get("GET", "input");
        if ($key == "") {
            return $get;
        } else {
            if (isset($get[$key]) and trim($get[$key]) != "") {
                return $get[$key];
            } else {
                return false;
            }
        }
    }

    public static function files($key = "")
    {
        $files = Base::get("FILES", "input");
        if ($files == "") {
            return $files;
        } else {
            if (isset($files[$key]) and $files[$key] != "") {
                return $files[$key];
            } else {
                return false;
            }
        }
    }

    public static function request($key = "")
    {
        $request = Base::get("REQUEST", "input");
        if ($key == "") {
            return $request;
        } else {
            if (isset($request[$key]) and trim($request[$key]) != "") {
                return $request[$key];
            } else {
                return false;
            }
        }
    }

}
