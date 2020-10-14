<?php
namespace Model;

class XI_Model{
  
    function __construct(){
        global $benchmark, $cache, $config, $cookie, $file, $form, $hook, $http, $input, $lang, $server, $session, $upload, $uri, $load, $db;

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
            if(sizeof($this->config->item("autoload.connect_db")) > 1){
                foreach($this->config->item("autoload.connect_db") as $db_group_name){
                    $this->db->$db_group_name = $db[$db_group_name];
                }
            }else{
                $this->db = $db;
            }
        }

        if(is_array($this->config->item("autoload.model")) and sizeof($this->config->item("autoload.model")) > 0){
            foreach($this->config->item("autoload.model") as $key => $value){
                $this->model($key, $value);
            }
        }
    }

    public function view($filename, $data = array()){
        if(pathinfo($filename, PATHINFO_EXTENSION) != "php"){
          $filename = $filename.".php";
        }
        if(is_array($data) and sizeof($data)){
          extract($data);
        }
        $path = APP . "View/" . $filename;
        if(file_exists($path)){
          require($path);
        }
      }
    
      public function model($name, $method, $parameters = ""){
        $model_path = APP . 'Model/' . $name . '.php';
        require_once($model_path);
        $name = "Model\\".$name;
        if($parameters == ""){
            return $this->$method = new $name();
        }else{
            return $this->$method = new $name($parameters);
        }
      }
    
      public function helper($name){
          $model_path = APP . 'Helpers/' . $name . '_helper.php';
          require_once($model_path);
      }

}
