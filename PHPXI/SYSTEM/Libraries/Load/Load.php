<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Load;

class Load
{
    private static $loaded_helper = [];

    public static function view($view, $data = [])
    {
        $path = APPLICATION_PATH . 'Views/' . $view;
        if(substr($view, -4) != ".php"){
            $path .= ".php";
        }
        extract($data);
        require($path);
    }

    public static function helper($name)
    {
        $path = APPLICATION_PATH . 'Helpers/' . ucfirst($name) . '_helper.php';
        if(!in_array($name, self::$loaded_helper)){
            require $path;
            self::$loaded_helper[] = $name;
        }
    }

}
