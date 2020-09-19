<?php



$msure = microtime (); 
$msure = explode (' ', $msure ); 
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);


require_once(PHPXI . "APPLICATION/Config/config.php");

require_once(PHPXI . "APPLICATION/Config/database.php");

require_once(PHPXI . "APPLICATION/Config/autoload.php");

require_once(PHPXI . "APPLICATION/Config/upload.php");

if(!defined("MODE")){
    define("MODE", "production");
}

require_once(PHPXI . "/SYSTEM/Debug/Debug.php");

require_once(PHPXI . "/SYSTEM/Debug/Reporting.php");

if(isset($config["autoload"]["config"]) and sizeof($config["autoload"]["config"]) > 0){
  foreach($config["autoload"]["config"] as $conf){
    $path = PHPXI . 'APPLICATION/Config/' . $conf . '.php';
    if(file_exists($path)){
      require_once($path);
    }
  }
}

if(sizeof($config["autoload"]["helper"]) > 0){
  foreach($config["autoload"]["helper"] as $helper){
    $path = PHPXI . 'SYSTEM/Helpers/' . $helper . '.php';
    if(file_exists($path)){
      require_once($path);
    }else{
      $path = PHPXI . 'APPLICATION/Helpers/' . $helper . '_helper.php';
      if(file_exists($path)){
        require_once($path);
      }
    }
  }
}

if(sizeof($config["autoload"]["libraries"]) > 0){
  foreach($config["autoload"]["libraries"] as $library){
    $path = PHPXI . 'APPLICATION/Libraries/' . $library . '.php';
    if(file_exists($path)){
      require_once($path);
    }
  }
}

require_once(PHPXI . "/SYSTEM/Config/Config.php");

require_once(PHPXI . "/SYSTEM/Input/Input.php");

require_once(PHPXI . "/SYSTEM/Languages/Languages.php");

require_once(PHPXI . "/SYSTEM/Database/Mysqli.php");


if($config["autoload"]["db"]){
  if(sizeof($config["autoload"]["connect_db"]) > 1){
      $db = array();
      foreach($config["autoload"]["connect_db"] as $database){
          $db_config = $config["db"][$database];
          $db[$database] = new PHPXI\SYSTEM\MYSQLI\DB($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
      }
  }else{
      $db_config = $config["db"][$config["autoload"]["connect_db"][0]];
      $db = new PHPXI\SYSTEM\MYSQLI\DB($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
  }
}

require_once(PHPXI . "/SYSTEM/Hook/Hook.php");

require_once(PHPXI . "/SYSTEM/Upload/Upload.php");

require_once(PHPXI . "/SYSTEM/Route/Route.php");

require_once(PHPXI . "/SYSTEM/Session/Session.php");

require_once(PHPXI . "/SYSTEM/Form/Form.php");

require_once(PHPXI . "/SYSTEM/File/File.php");

require_once(PHPXI . "/SYSTEM/Server/Server.php");

require_once(PHPXI . "/SYSTEM/Http/Http.php");

require_once(PHPXI . "/SYSTEM/Cookie/Cookie.php");

require_once(PHPXI . "/SYSTEM/Cache/HTML.php");

require_once(PHPXI . "/SYSTEM/Model.php");

require_once(PHPXI . "/SYSTEM/Controller.php");

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
            require_once(PHPXI . 'APPLICATION/Controller/' . $controller);
            $class = new $controller_name();
            if(method_exists($class, "robots_txt")){
              $class->robots_txt();
            }
          }else{
            if(isset($uri[0]) and trim($uri[0], "/") != ""){
                $uri_controller = $uri[0].".php";
                $path = PHPXI . 'APPLICATION/Controller/' . $uri_controller;
                if(file_exists($path)){
                    $controller = $uri_controller;
                    $controller_name = $uri[0];
                }
            }
            $path = PHPXI . 'APPLICATION/Controller/' . $controller;
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

$msure = microtime (); 
$msure = explode (' ', $msure); 
$msure = $msure[1] + $msure[0]; 
define("LOAD_TIMER", round (($msure - TIMER_START), 5));
define("LOAD_MEMORY", round(memory_get_peak_usage()/1048576, 3));