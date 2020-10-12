<?php
namespace Controller;

class XI_Controller{
  
  private $html;

  function __construct(){
    
    $this->config = new \PHPXI\SYSTEM\Config();

    $this->lang = new \PHPXI\SYSTEM\Languages();
    
    $this->form = new \PHPXI\SYSTEM\Form();
    
    $this->file = new \PHPXI\SYSTEM\File();

    if($this->config->item("autoload.input")){
        $this->input = new \PHPXI\SYSTEM\Input();
    }
        
    if($this->config->item("config.session")){
        $this->session = new \PHPXI\SYSTEM\Session();
    }
    
    $this->hook = new \PHPXI\SYSTEM\Hook();

    if($this->config->item("autoload.upload")){
        $this->upload = new \PHPXI\SYSTEM\Upload();
        $this->upload->config($this->config->item("upload"));
    }

    $this->uri = new \PHPXI\SYSTEM\Uri();
        
    $this->server = new \PHPXI\SYSTEM\Server();
    
    $this->http = new \PHPXI\SYSTEM\Http();
    
    $this->cookie = new \PHPXI\SYSTEM\Cookie();

    $this->benchmark = new \PHPXI\SYSTEM\Benchmark();

  }

  function view($filename, $data = array()){
    if(pathinfo($filename, PATHINFO_EXTENSION) != "php"){
      $filename = $filename.".php";
    }
    if(is_array($data) and sizeof($data)){
      extract($data);
    }
    $path = PHPXI . "APPLICATION/View/" . $filename;
    if(file_exists($path)){
      require($path);
    }
  }

  function model($name, $method, $parameters = ""){
    $model_path = PHPXI . 'APPLICATION/Model/' . $name . '.php';
    $name = "Model\\".$name;
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
          if(!isset($database["charset"])){
              $database["charset"] = "utf8";
          }
          if(!isset($database["prefix"])){
              $database["prefix"] = "";
          }
          return new \PHPXI\SYSTEM\MYSQLI\DB($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
      }else{
          $database = $this->config->item("database.".$database);
          return new \PHPXI\SYSTEM\MYSQLI\DB($database["host"], $database["user"], $database["password"], $database["name"], $database["charset"], $database["prefix"]);
      }
  }
  
}
