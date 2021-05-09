<?php
namespace PHPXI\Libraries\Base;

class Base
{

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
            "lang" => []
        ],
        "validation" => [
            "_token" => null,
        ],
    ];

    private static $application_config_file = [
        "autoload",
        "cache",
        "config",
        "cookie",
        "database",
        "filters",
        "language",
        "session",
        "upload",
    ];

    public static function main()
    {
        foreach (self::$application_config_file as $file) {
            self::config_load($file);
        }

        $autoload_config = self::$data['config']['autoload']['config'];
        if (is_array($autoload_config) and sizeof($autoload_config) > 0) {
            foreach ($autoload_config as $row) {
                self::config_load($row);
            }
        }

        if (isset($_SERVER)) {
            self::$data['server'] = $_SERVER;
        }
        if(isset($_SESSION)){
            self::$data['session'] = $_SESSION;
        }

        if(isset($_COOKIE)){
            $cookie_prefix = self::get("cookie", "config");
            $cookie_prefix = $cookie_prefix['prefix'];
            foreach($_COOKIE as $key => $value){
                if(mb_substr($key, 0, strlen($cookie_prefix)) == $cookie_prefix){
                    $id = mb_substr($key, strlen($cookie_prefix, strlen($key)));
                    self::$data['cookie'][$key] = $value;
                }
            }
        }

        if(isset($_GET)){
            self::$data['input']['GET'] = self::input_clear($_GET);
        }
        if(isset($_POST) && self::get('REQUEST_METHOD', "server") === "POST"){
            self::$data['input']['POST'] = self::input_clear($_POST);
        }
        if(isset($_FILES) && self::get('REQUEST_METHOD', "server") === "POST"){
            self::$data['input']['FILES'] = self::input_clear($_FILES);
        }
        if(isset($_REQUEST)){
            self::$data['input']['REQUEST'] = self::input_clear($_REQUEST);
        }

        $lang_setting = self::get("language", "config");
        if(isset($lang_setting['multi']) && $lang_setting['multi']){
            $request_uri = trim(mb_strtolower(mb_substr(self::get("PHP_SELF", "server"), strlen(self::get("SCRIPT_NAME", "server")), strlen(self::get("PHP_SELF", "server")), "UTF-8"), "UTF-8"), "/");
            $parse = explode("/", $request_uri);
            if (isset($parse[0]) and trim($parse[0]) != "") {
                $load_language_file = $parse[0];
            } else {
                $load_language_file = $lang_setting['default'];
            }
        }else{
            $load_language_file = $lang_setting['default'];
        }
        $lang_setting['set'] = $load_language_file;
        self::set("language", $lang_setting, "config");
        self::lang_load($load_language_file);

        if(self::get("REQUEST_METHOD", "server") !== "POST"){
            self::token_create();
        }else{
            self::set("_token", self::get("_token", "session"), "validation");
        }
    }

    public static function set($key, $value, $property)
    {
        if($key == ""){
            self::$data[$property] = $value;
        }else{
            self::$data[$property][$key] = $value;
        }
    }

    public static function get($key, $property)
    {
        if($key == ""){
            if (isset(self::$data[$property])) {
                return self::$data[$property];
            } else {
                return false;
            }
        }else{
            if (isset(self::$data[$property][$key])) {
                return self::$data[$property][$key];
            } else {
                return false;
            }
        }
    }

    private static function config_load($file)
    {
        $path = APPLICATION_PATH . 'Config/' . ucfirst($file) . '.php';
        if (file_exists($path) || DEVELOPMENT) {
            $config = [];
            require_once $path;
            foreach ($config as $key => $value) {
                self::$data['config'][$file][$key] = $value;
            }
        }
    }

    private static function token_create()
    {
        $token = rand(0, 999) . time() . rand(0, 999);
        $token = md5($token);
        self::set("_token", $token, "validation");
        $_SESSION["_token"] = $token;
    }

    private static function lang_load($file)
    {
        $path = APPLICATION_PATH . "Languages/" . $file . "/app.php";
        if (file_exists($path)) {
            $lang = [];
            require_once $path;
            self::$data['language']['lang'] = $lang;
        }
    }
    
    private static function input_clear($input)
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
