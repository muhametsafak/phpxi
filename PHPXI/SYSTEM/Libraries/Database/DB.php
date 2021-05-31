<?php
/**
 * DB.php
 *
 * This file is part of PHPXI.
 *
 * @package    DB.php @ 2021-05-11T20:44:36.315Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace PHPXI\Libraries\Database;

class DB
{

    private $host = 'localhost';

    private $port = 3306;

    private $user;

    private $password;

    private $name;

    private $prefix = '';

    private $charset = 'utf-8';

    private $collation = 'utf8mb4_general_ci';

    private $driver = 'mysql';

    private $class = 'mysqli';

    private $db;

    public function __construct($db_info)
    {
        $this->db = $this->connect($db_info);

        return $this->db;
    }

    /**
     * @param array $name
     */
    public function connect(array $name)
    {
        if (is_array($name)) {
            $this->host = $name['host'];
            $this->port = $name['port'];
            $this->user = $name['user'];
            $this->password = $name['password'];
            $this->name = $name['name'];
            if (isset($name['prefix']) and $name['prefix'] != "") {
                $this->prefix = $name['prefix'];
            }
            if (isset($name['charset']) and $name['charset'] != "") {
                $this->charset = $name['charset'];
            }
            if (isset($name['collation']) and $name['collation'] != "") {
                $this->collation = $name['collation'];
            }
            if (isset($name['driver']) and $name['driver'] != "") {
                $this->driver = $name['driver'];
            }
            if (isset($name['class']) and $name['class'] != "") {
                $this->class = $name['class'];
            }
        }

        $connection_config = [
            "host" => $this->host,
            "port" => $this->port,
            "user" => $this->user,
            "password" => $this->password,
            "name" => $this->name,
            "prefix" => $this->prefix,
            "charset" => $this->charset,
            "collation" => $this->collation,
            "driver" => $this->driver
        ];

        switch (strtolower($this->class)) {
            case 'mysqli':
                return new \PHPXI\Libraries\Database\MySQLi($connection_config);
                break;
            case 'pdo':
                return new \PHPXI\Libraries\Database\PDO($connection_config);
                break;
            default:
                $custom_db_class_name = $this->class;

                return new $custom_db_class_name($connection_config);
                break;
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->db->$name(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        return (new self())->$name(...$arguments);
    }

}
