<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Load;

class Load
{

    public static function view($view, $data = [])
    {
        $path = APPLICATION_PATH . 'Views/' . $view;
        if (substr($view, -4) != ".php") {
            $path .= ".php";
        }
        extract($data);
        require $path;
    }

    public static function helper($name)
    {
        $path = APPLICATION_PATH . 'Helpers/' . ucfirst($name) . '_helper.php';
        require_once $path;
    }

}
