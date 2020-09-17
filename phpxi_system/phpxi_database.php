<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}


if($config["autoload"]["db"]){
    if(sizeof($config["autoload"]["connect_db"]) > 1){
        $db = array();
        foreach($config["autoload"]["connect_db"] as $database){
            $db_config = $config["db"][$database];
            $db[$database] = new PHPXI_MySQLi($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
        }
    }else{
        $db_config = $config["db"][$config["autoload"]["connect_db"][0]];
        $db = new PHPXI_MySQLi($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
    }
}


/** MONGODB */
$mongodb = array();
if(isset($config["mongodb"]) and is_array($config["mongodb"]) and sizeof($config["mongodb"]) > 0){
    foreach($config["mongodb"] as $key => $value){
        if($value["connect"]){
            $mongodb[$key] = new PHPXI_MongoDB($value["host"], $value["user"], $value["password"], $value["name"], $value["port"]);
        }
    }
}
