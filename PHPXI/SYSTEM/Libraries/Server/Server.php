<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Server;

use \PHPXI\Libraries\Base\Base as Base;

class Server
{

    public static function get($key)
    {
        return Base::get($key, "server");
    }

    public static function set($key, $value)
    {
        Base::set($key, $value, "server");
        return new self();
    }

}
