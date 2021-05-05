<?php
namespace PHPXI\Libraries\Validation;

use \PHPXI\Libraries\Session\Session as Session;
use \PHPXI\Libraries\Server\Server as Server;
use \PHPXI\Libraries\Input\Input as Input;

class Token
{
    private static $token;

    public static function autoload()
    {
        if(Server::get("REQUEST_METHOD") !== "POST"){
            self::create();
        }else{
            self::$token = Session::get("_token");
        }
    }

    public static function create()
    {
        self::$token = time() . Session::id() . rand(0, 999);
        self::$token = md5(self::$token);
        self::set(self::$token);
    }

    public static function set($value)
    {
        Session::set("_token", $value);
    }

    public static function get()
    {
        return self::$token;
    }

    public static function verify()
    {
        if(Input::post("_token") === self::$token){
            return true;
        }else{
            return false;
        }
    }

}
