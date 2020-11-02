<?php
namespace Model;

class XI_Model{
    public $db;

    function __construct(){
        global $benchmark, $cache, $config, $cookie, $file, $form, $hook, $http, $input, $lang, $server, $session, $upload, $uri, $load, $db, $models;

        $this->benchmark = $benchmark;
        $this->config = $config;
        $this->cookie = $cookie;
        $this->file = $file;
        $this->form = $form;
        $this->hook = $hook;
        $this->http = $http;
        $this->input = $input;
        $this->lang = $lang;
        $this->server = $server;
        $this->session = $session;
        $this->upload = $upload;
        $this->uri = $uri;

        if($this->config->item("autoload.db")){
            $this->db = $db;
        }

        if(is_array($models) and sizeof($models) > 0){
            foreach($models as $key => $value){
                $this->$key = $value;
            }
        }
    }
    
    public function model($name, $method, $parameters = ""){
        $model_path = APP . 'Model/' . $name . '.php';
        require_once($model_path);
        $name = "Model\\".$name;
        if($parameters == ""){
            $this->$method = new $name();
        }else{
            $this->$method = new $name($parameters);
        }
        if(method_exists($this->$method, "autoload")){
            $this->$method->autoload();
        }
    }

    public function helper($name){
        $model_path = APP . 'Helpers/' . $name . '_helper.php';
        require_once($model_path);
    }


}
