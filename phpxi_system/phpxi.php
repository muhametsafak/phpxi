<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}

require_once(APP . "config/config.php");

require_once(SYSTEM . "phpxi_debug.php");

require_once(APP . "config/database.php");

require_once(APP . "config/autoload.php");

require_once(APP . "config/upload.php");

if(isset($config["autoload"]["config"]) and sizeof($config["autoload"]["config"]) > 0){
  foreach($config["autoload"]["config"] as $conf){
    $path = APP . 'config/' . $conf . '.php';
    if(file_exists($path)){
      require_once($path);
    }
  }
}

$config["autoload"]["helper"][] = "arrayObject";  //Helper arrayObject include

if(sizeof($config["autoload"]["helper"]) > 0){
  foreach($config["autoload"]["helper"] as $helper){
    $path = SYSTEM . 'helper/' . $helper . '_helper.php';
    if(file_exists($path)){
      require_once($path);
    }else{
      $path = APP . 'helper/' . $helper . '_helper.php';
      if(file_exists($path)){
        require_once($path);
      }
    }
  }
}

require_once(SYSTEM . "phpxi_config.php");

require_once(SYSTEM . "phpmailer.php");

require_once(SYSTEM . "smtp.phpmailer.php");

require_once(SYSTEM . "pop3.phpmailer.php");

require_once(SYSTEM . "phpxi_input.php");

require_once(SYSTEM . "phpxi_languages.php");

require_once(SYSTEM . "phpxi_mysqli.php");

require_once(SYSTEM . "phpxi_pdo.php");

require_once(SYSTEM . "phpxi_mongodb.php");

require_once(SYSTEM . "phpxi_database.php");

require_once(SYSTEM . "phpxi_plugin.php");

require_once(SYSTEM . "phpxi_upload.php");

require_once(SYSTEM . "phpxi_route.php");

require_once(SYSTEM . "phpxi_session.php");

require_once(SYSTEM . "phpxi_form.php");

require_once(SYSTEM . "phpxi_file.php");

require_once(SYSTEM . "phpxi_model.php");

require_once(SYSTEM . "phpxi_controller.php");

class PHPXI{
    public $method;
    
    function __construct(){
        global $config;
        if(isset($_GET['uri'])){
          $uri = array_filter(explode('/', @$_GET['uri']));
        }else{
          $uri = array_filter(explode('/', "/"));
        }
        
        $this->method = explode("|", strtoupper($config['controller']['method']));
        
        if(in_array($_SERVER["REQUEST_METHOD"], $this->method)){
          $controller_name = $config["controller"]['default_controller'];
          $controller = $config["controller"]['default_controller'].".php";

          if(isset($uri[0]) and $uri[0] == "robots.txt"){
            require_once(APP . 'controller/' . $controller);
            $class = new $controller_name();
            if(method_exists($class, "robots_txt")){
              $class->robots_txt();
            }
          }else{
            if(isset($uri[0]) and trim($uri[0], "/") != ""){
                $uri_controller = $uri[0].".php";
                $path = APP . 'controller/' . $uri_controller;
                if(file_exists($path)){
                    $controller = $uri_controller;
                    $controller_name = $uri[0];
                }
            }
            $path = APP . 'controller/' . $controller;
            require_once($path);
            
            $class = new $controller_name();
            $default_method = $config["controller"]['default_method'];
            if(isset($uri[1])){
                if(method_exists($class, $uri[1])){
                    $method = $uri[1];
                    $class->$method();
                }else{
                    $class->$default_method();
                }
            }else{
                $class->$default_method();
            }
          }

        }
    }

}
