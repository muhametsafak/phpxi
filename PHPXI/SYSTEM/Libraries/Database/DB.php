<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Database;

use \PHPXI\Libraries\Config\Config as Config;

class DB
{
    private static $host = 'localhost';
    private static $user;
    private static $password;
    private static $name;
    private static $prefix = '';
    private static $charset = 'utf-8';
    private static $collation = 'utf8mb4_general_ci';
    private static $driver = 'mysql';
    private static $class = 'mysqli';

    public static function connect($name)
    {
        if (is_array($name)) {
            self::$host = $name['host'];
            self::$user = $name['user'];
            self::$password = $name['password'];
            self::$name = $name['name'];
            if (isset($name['prefix']) and $name['prefix'] != "") {
                self::$prefix = $name['prefix'];
            }
            if (isset($name['charset']) and $name['charset'] != "") {
                self::$charset = $name['charset'];
            }
            if (isset($name['collation']) and $name['collation'] != "") {
                self::$collation = $name['collation'];
            }
            if (isset($name['driver']) and $name['driver'] != "") {
                self::$driver = $name['driver'];
            }
            if (isset($name['class']) and $name['class'] != "") {
                self::$class = $name['class'];
            }
        } else {
            self::$host = Config::get("database." . $name . ".host");
            self::$user = Config::get("database." . $name . ".user");
            self::$password = Config::get("database." . $name . ".password");
            self::$name = Config::get("database." . $name . ".name");
            self::$prefix = Config::get("database." . $name . ".prefix");
            self::$charset = Config::get("database." . $name . ".charset");
            self::$collation = Config::get("database." . $name . ".collation");
            self::$driver = Config::get("database." . $name . ".driver");
            self::$class = Config::get("database." . $name . ".class");
        }

        $connection_config = [
            "host" => self::$host,
            "user" => self::$user,
            "password" => self::$password,
            "name" => self::$name,
            "prefix" => self::$prefix,
            "charset" => self::$charset,
            "collation" => self::$collation,
            "driver" => self::$driver,
        ];

        switch (strtolower(self::$class)) {
            case 'mysqli':
                return new \PHPXI\Libraries\Database\MySQLi($connection_config);
                break;
            case 'pdo':
                return new \PHPXI\Libraries\Database\PDO($connection_config);
                break;
            default:
                $custom_db_class_name = self::$class;
                return new $custom_db_class_name($connection_config);
                break;
        }
    }

}
