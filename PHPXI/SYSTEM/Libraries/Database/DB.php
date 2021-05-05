<?php
namespace PHPXI\Libraries\Database;

use \PHPXI\Libraries\Config\Config as Config;

class DB
{

    public static function connect($name){
        $db_config = Config::get("database." . $name);
        return new \PHPXI\Libraries\Database\Mysqli(
            Config::get("database." . $name . ".host"),
            Config::get("database." . $name . ".user"),
            Config::get("database." . $name . ".password"),
            Config::get("database." . $name . ".name"),
            Config::get("database." . $name . ".charset"),
            Config::get("database." . $name . ".prefix")
        );
    }

}
