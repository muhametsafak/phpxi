<?php
namespace PHPXI\Libraries\Load;

class Load
{

    public static function view($view, $data = []){
        $path = APPLICATION_PATH . 'Views/' . $view;
        if(substr($view, -4) != ".php"){
            $path .= ".php";
        }
        extract($data);
        require($path);
    }

    public static function model(){

    }

    public static function helper(){

    }

}
