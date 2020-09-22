<?php

class PHPXI_Model{


    function __construct(){
    
        $this->config = new PHPXI\SYSTEM\Config();
        
        $this->lang = new PHPXI\SYSTEM\Languages();

        $this->form = new PHPXI\SYSTEM\Form();

        $this->file = new PHPXI\SYSTEM\File();

        if($this->config->item("autoload.input")){
            $this->input = new PHPXI\SYSTEM\Input();
        }
        
        if($this->config->item("session")){
            $this->session = new PHPXI\SYSTEM\Session();
        }

        if($this->config->item("autoload.db")){
            global $db;
            if(sizeof($this->config->item("autoload.connect_db")) > 1){
                foreach($this->config->item("autoload.connect_db") as $db_group_name){
                    $this->db->$db_group_name = $db[$db_group_name];
                }
            }else{
                $this->db = $db;
            }
        }
        
        $this->hook = new PHPXI\SYSTEM\Hook();
        if($this->config->item("autoload.upload")){
            $this->upload = new PHPXI\SYSTEM\Upload();
        }
        
        $this->uri = new PHPXI\SYSTEM\Route();
        
        $this->server = new PHPXI\SYSTEM\Server();
        
        $this->http = new PHPXI\SYSTEM\Http();
        
        $this->cookie = new PHPXI\SYSTEM\Cookie();

        $this->benchmark = new PHPXI\SYSTEM\Benchmark();

        if(is_array($this->config->item("autoload.model")) and sizeof($this->config->item("autoload.model")) > 0){
            foreach($this->config->item("autoload.model") as $key => $value){
                $this->model($key, $value);
            }
        }
    }

    function model($name, $method, $parameters = ""){
        $model_path = PHPXI . 'APPLICATION/Model/' . $name . '.php';
        require_once($model_path);
        if($parameters == ""){
            return $this->$method = new $name();
        }else{
            return $this->$method = new $name($parameters);
        }
    }
    
    function helper($name){
        $model_path = PHPXI . 'APPLICATION/Helpers/' . $name . '_helper.php';
        require_once($model_path);
    }

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
            if(!isset($database[""])){
                $database["charset"] = "utf8";
            }
            if(!isset($database["prefix"])){
                $database["prefix"] = "";
            }
            return new PHPXI\SYSTEM\MYSQLI\DB($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
        }else{
            $database = $this->config->item("db.".$database);
            return new PHPXI\SYSTEM\MYSQLI\DB($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
        }
    }
}
