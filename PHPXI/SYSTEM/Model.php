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
        
        if($this->config->item("autoload.session")){
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
}
