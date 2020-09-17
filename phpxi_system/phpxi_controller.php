<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}


class PHPXI_CONTROLLER{
  
  private $html;

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
    
    if($this->config->item("autoload.phpmailer")){
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer();
    }
    $this->plugin = new PHPXI_PLUGIN();
    if($this->config->item("autoload.upload")){
        $this->upload = new PHPXI_UPLOAD();
    }
    $this->uri = new PHPXI_ROUTE();
  }

  function view($filename, $data = array()){
    if(pathinfo($filename, PATHINFO_EXTENSION) != "php"){
      $filename = $filename.".php";
    }
    if(is_array($data) and sizeof($data)){
      extract($data);
    }
    $path = APP . "view/" . $filename;
    if(file_exists($path)){
      ob_start();
      require($path);
      $this->html .= ob_get_contents();
      ob_get_clean();
    }
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


  function __destruct(){
    echo $this->html;
  }


}

