<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}

class PHPXI_Model{

    function __construct(){
    
        $this->config = new PHPXI_CONFIG();
        
        $this->lang = new PHPXI_LANGUAGES();

        $this->form = new PHPXI_FORM();

        $this->file = new PHPXI_FILE();

        if($this->config->item("autoload.input")){
            $this->input = new PHPXI_INPUT();
        }
        
        if($this->config->item("autoload.session")){
            $this->session = new PHPXI_SESSION();
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
    
        global $mongodb;
        if(sizeof($mongodb) > 0){
            foreach($mongodb as $key => $value){
                $this->mongodb->$key = $value;
            }
        }
        
        if($this->config->item("autoload.phpmailer")){
            $this->mailer = new PHPMailer\PHPMailer\PHPMailer();
        }
        $this->plugin = new PHPXI_PLUGIN();
        if($this->config->item("autoload.upload")){
            $this->upload = new PHPXI_UPLOAD();
        }
        $this->uri = new PHPXI_ROUTE();
    }

    function model($name, $method, $parameters = ""){
        $model_path = APP . 'model/' . $name . '.php';
        require_once($model_path);
        if($parameters == ""){
            return $this->$method = new $name();
        }else{
            return $this->$method = new $name($parameters);
        }
    }
    
    function helper($name){
        $model_path = APP . 'helper/' . $name . '_helper.php';
        require_once($model_path);
    }
}
