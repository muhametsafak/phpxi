<?php

class PHPXI_Controller{
  
  private $html;

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
    
    $this->hook = new PHPXI\SYSTEM\Hook();

    if($this->config->item("autoload.upload")){
        $this->upload = new PHPXI\SYSTEM\Upload();
    }

    $this->uri = new PHPXI\SYSTEM\Route();
        
    $this->server = new PHPXI\SYSTEM\Server();
    
    $this->http = new PHPXI\SYSTEM\Http();
    
    $this->cookie = new PHPXI\SYSTEM\Cookie();
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
      ob_start();
      require($path);
      $this->html .= ob_get_contents();
      ob_get_clean();
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


  function __destruct(){
    echo $this->html;
    $this->html = null;
  }


}
