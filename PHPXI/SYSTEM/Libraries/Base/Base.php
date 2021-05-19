<?php
/**
 * Base.php
 *
 * This file is part of PHPXI.
 *
 * @package    Base.php @ 2021-05-11T20:15:51.180Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6
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

namespace PHPXI\Libraries\Base;

class Base
{

    /**
     * @var array
     */
    public static $data = [
        "request" => [],
        "responsive" => [],
        "server" => [],
        "session" => [],
        "cookie" => [],
        "config" => [],
        "input" => [
            "GET" => [],
            "POST" => [],
            "FILES" => [],
            "REQUEST" => []
        ],
        "language" => [
            "settings" => [],
            "lang" => []
        ],
        "validation" => [
            "_token" => null
        ],
        "benchmark" => [],
        "cache" => [
            "file" => WEIGHT_PATH . 'Cache/',
            "timeout" => 86400,
            "content" => "",
            "path" => "",
            "fullpath" => "",
            "gzip" => false
        ]
    ];

    /**
     * @var mixed
     */
    public static $errorHandler = false;

    /**
     * @var string
     */
    public static $errorHandlerOutput = '';

    /**
     * @var mixed
     */
    public static $errorHandlerDie = true;

    /**
     * @var array
     */
    public static $views = [];

    /**
     * @var array
     */
    public static $db = [];

    /**
     * @var array
     */
    public static $models = [];

    /**
     * @var array
     */
    public static $route = [
        'patterns' => [
            ':all[0-9]?' => '(.*)',
            ':any[0-9]?' => '([^/]+)',
            ':id[0-9]?' => '(\d+)',
            ':int[0-9]?' => '(\d+)',
            ':number[0-9]?' => '([+-]?([0-9]*[.])?[0-9]+)',
            ':float[0-9]?' => '([+-]?([0-9]*[.])?[0-9]+)',
            ':bool[0-9]?' => '(true|false|1|0)',
            ':string[0-9]?' => '([\w\-_]+)',
            ':slug[0-9]?' => '([\w\-_]+)',
            ':uuid[0-9]?' => '([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})',
            ':date[0-9]?' => '([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))'
        ],
        'last' => [],
        'prefix' => '',
        'get' => [],
        'post' => [],
        'head' => [],
        'put' => []
    ];

    public function __construct()
    {

        $autoload_config = \Config\Autoload::CONFIG;
        if (is_array($autoload_config) and sizeof($autoload_config) > 0) {
            foreach ($autoload_config as $row) {
                self::config_load($row);
            }
        }

        if (isset($_SERVER)) {
            self::$data['server'] = $_SERVER;
        }
        if (isset($_SESSION)) {
            self::$data['session'] = $_SESSION;
        }

        if (isset($_COOKIE)) {
            $cookie_prefix = \Config\Cookie::PREFIX;
            foreach ($_COOKIE as $key => $value) {
                if (mb_substr($key, 0, strlen($cookie_prefix)) == $cookie_prefix) {
                    $id = mb_substr($key, strlen(substr($cookie_prefix, 0, strlen($key))));
                    self::$data['cookie'][$key] = $value;
                }
            }
        }

        if (isset($_GET)) {
            self::$data['input']['GET'] = self::input_clear($_GET);
        }
        if (isset($_POST) && self::get('REQUEST_METHOD', "server") === "POST") {
            self::$data['input']['POST'] = self::input_clear($_POST);
        }
        if (isset($_FILES) && self::get('REQUEST_METHOD', "server") === "POST") {
            self::$data['input']['FILES'] = self::input_clear($_FILES);
        }
        if (isset($_REQUEST)) {
            self::$data['input']['REQUEST'] = self::input_clear($_REQUEST);
        }

        if (\Config\Config::MULTI_LANGUAGE) {
            $request_uri = trim(mb_strtolower(mb_substr(self::get("PHP_SELF", "server"), strlen(self::get("SCRIPT_NAME", "server")), strlen(self::get("PHP_SELF", "server")), "UTF-8"), "UTF-8"), "/");
            $parse = explode("/", $request_uri);
            if (isset($parse[0]) and trim($parse[0]) != "") {
                $load_language_file = $parse[0];
            } else {
                $load_language_file = \Config\Config::DEFAULT_LANGUAGE;
            }
        } else {
            $load_language_file = \Config\Config::DEFAULT_LANGUAGE;
        }
        self::lang_load($load_language_file);
        self::$data['language']['settings']['set'] = $load_language_file;

        if (self::get("REQUEST_METHOD", "server") !== "POST") {
            self::token_create();
        } else {
            self::set("_token", self::get("_token", "session"), "validation");
        }

        self::$models['request'] = new \PHPXI\Libraries\Request\Request();
        self::$models['response'] = new \PHPXI\Libraries\Response\Response();
        self::$models['load'] = new \PHPXI\Libraries\Load\Load();

        $autoload_libraries = \Config\Autoload::LIBRARIES;
        if (sizeof($autoload_libraries) > 0) {
            foreach ($autoload_libraries as $lib_id => $library) {
                self::$models[$lib_id] = new $library();
            }
        }

        $autoload_db = \Config\Autoload::DB;
        if (sizeof($autoload_db) > 0) {
            foreach ($autoload_db as $key => $database) {
                self::$db[$key] = new \PHPXI\Libraries\Database\DB($database);
            }
        }

        if (sizeof(\Config\Autoload::HELPER) > 0) {
            foreach (\Config\Autoload::HELPER as $row) {
                $path = APPLICATION_PATH . 'Helpers/' . ucfirst($row) . '_helper.php';
                require_once $path;
            }
        }

        $autoload_model = \Config\Autoload::MODEL;
        if (is_array($autoload_model) && sizeof($autoload_model) > 0) {
            foreach ($autoload_model as $model_key => $model_value) {
                $model_key = strtolower($model_key);
                self::$models[$model_key] = new $model_value();
            }
        }

    }

    /**
     * @param $key
     * @param $value
     * @param $property
     */
    public static function set($key, $value, $property): void
    {
        if ($key == "") {
            self::$data[$property] = $value;
        } else {
            self::$data[$property][$key] = $value;
        }
    }

    /**
     * @param $key
     * @param $property
     */
    public static function get($key, $property)
    {
        if ($key == "") {
            if (isset(self::$data[$property])) {
                return self::$data[$property];
            } else {
                return false;
            }
        } else {
            if (isset(self::$data[$property][$key])) {
                return self::$data[$property][$key];
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $file
     */
    private static function config_load(string $file): void
    {
        $path = APPLICATION_PATH . 'Config/' . ucfirst($file) . '.php';
        if (file_exists($path) || DEVELOPMENT) {
            $config = [];
            require_once $path;
            $file = strtolower($file);
            foreach ($config as $key => $value) {
                self::$data['config'][$file][$key] = $value;
            }
        }
    }

    private static function token_create(): void
    {
        $token = rand(0, 999) . time() . rand(0, 999);
        $token = md5($token);
        self::set("_token", $token, "validation");
        self::set("_token", $token, "session");
        $_SESSION["_token"] = $token;
    }

    /**
     * @param string $file
     */
    private static function lang_load(string $file): void
    {
        $path = APPLICATION_PATH . "Languages/" . $file . "/app.php";
        if (file_exists($path)) {
            $lang = [];
            require_once $path;
            self::$data['language']['lang'] = $lang;
        }
    }

    /**
     * @param array $input
     * @return mixed
     */
    private static function input_clear(array $input): array
    {
        $return = [];
        if (is_array($input) and sizeof($input) > 0) {
            foreach ($input as $key => $value) {
                if (is_array($value)) {
                    $return[$key] = self::input_clear($value);
                } else {
                    $return[$key] = trim(stripslashes($value));
                }
            }
        }

        return $return;
    }

}
