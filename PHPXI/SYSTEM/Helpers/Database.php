<?php

if(!function_exists("db_connect")){
    function db_connect($database = array()){
        if(is_array($database)){
            if(!isset($database["host"])){
                $database["host"] = "localhost";
            }
            if(!isset($database["user"])){
                $database["user"] = "root";
            }
            if(!isset($database["password"])){
                $database["password"] = "";
            }
            if(!isset($database["name"])){
                $database["name"] = "";
            }
            if(!isset($database["charset"])){
                $database["charset"] = "utf8";
            }
            if(!isset($database["prefix"])){
                $database["prefix"] = "";
            }
            return new PHPXI\Mysqli($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
        }else{
            global $config;
            $database = $config->item("database.".$database);
            return new PHPXI\Mysqli($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
        }
    }
}
