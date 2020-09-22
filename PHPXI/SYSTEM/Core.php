<?php

$msure = microtime (); 
$msure = explode (' ', $msure ); 
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);

require_once(PHPXI . "APPLICATION/Config/Config.php");

require_once(PHPXI . "APPLICATION/Config/Database.php");

require_once(PHPXI . "APPLICATION/Config/Autoload.php");

require_once(PHPXI . "APPLICATION/Config/Upload.php");

require_once(PHPXI . "APPLICATION/Config/Cache.php");

if(!defined("ENV")){
    define("ENV", "production");
}

mb_internal_encoding("UTF-8");

require_once(PHPXI . "/SYSTEM/Debug/Debug.php");

require_once(PHPXI . "/SYSTEM/Debug/Reporting.php");

if(isset($config["autoload"]["config"]) and sizeof($config["autoload"]["config"]) > 0){
  foreach($config["autoload"]["config"] as $conf){
    $path = PHPXI . 'APPLICATION/Config/' . ucfirst($conf) . '.php';
    if(file_exists($path)){
      require_once($path);
    }
  }
}

if(sizeof($config["autoload"]["helper"]) > 0){
  foreach($config["autoload"]["helper"] as $helper){
    $path = PHPXI . 'SYSTEM/Helpers/' . ucfirst($helper) . '.php';
    if(file_exists($path)){
      require_once($path);
    }else{
      $path = PHPXI . 'APPLICATION/Helpers/' . ucfirst($helper) . '_helper.php';
      if(file_exists($path)){
        require_once($path);
      }
    }
  }
}

if(sizeof($config["autoload"]["libraries"]) > 0){
  foreach($config["autoload"]["libraries"] as $library){
    $path = PHPXI . 'APPLICATION/Libraries/' . ucfirst($library) . '.php';
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

require_once(PHPXI . "/SYSTEM/Cache/Cache.php");

require_once(PHPXI . "/SYSTEM/Benchmark/Benchmark.php");

require_once(PHPXI . "/SYSTEM/Model.php");

require_once(PHPXI . "/SYSTEM/Controller.php");

class PHPXI{
    public $method;
    private $view;
    
    function __construct(){
        $this->config = new PHPXI\SYSTEM\Config();

        $uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
        if($uri != ""){
          $uri = array_filter(explode('/', $uri));
        }else{
          $uri = array("/");
        }
        
        $this->method = explode("|", strtoupper($this->config->item("controller.method")));
        
        if(in_array($_SERVER["REQUEST_METHOD"], $this->method)){
          $controller_name = ucfirst($this->config->item("controller.default_controller"));
          $controller = ucfirst($this->config->item("controller.default_controller")).".php";

          if(isset($uri[0]) and $uri[0] == "robots.txt"){
            require_once(PHPXI . 'APPLICATION/Controller/' . $controller);
            $class = new $controller_name();
            if(method_exists($class, "robots_txt")){
              $class->robots_txt();
            }
          }else{
            if($this->config->item("cache.status")){
              $this->cache = new PHPXI\SYSTEM\Cache();
              $this->cache->path($this->config->item("cache.path"))->timeout($this->config->item("cache.timeout"))->file("%%".md5(current_url())."%%.html");
              if(!$this->cache->is() || $this->cache->is_timeout()){

                ob_start();
                if(isset($uri[0]) and trim($uri[0], "/") != ""){
                  $uri_controller = ucfirst($uri[0]).".php";
                  $path = PHPXI . 'APPLICATION/Controller/' . $uri_controller;
                  if(file_exists($path)){
                      $controller = $uri_controller;
                      $controller_name = $uri[0];
                  }
                }
                $path = PHPXI . 'APPLICATION/Controller/' . $controller;
                require_once($path);
                $class = new $controller_name();
                $default_method = $this->config->item("controller.default_method");
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
                $this->view = ob_get_contents();
                ob_get_clean();
                
                if($this->config->item("cache.html_compressor")){
                  $this->cache->content(preg_replace("/\s+/", " ", $this->view));
                }else{
                  $this->cache->content($this->view);
                }
                if(!$this->cache->is()){
                  $this->cache->create();
                }else{
                  $this->cache->write();
                }
                echo $this->view;

              }else{
                echo $this->cache->read();
              }

            }else{
              ob_start();
              if(isset($uri[0]) and trim($uri[0], "/") != ""){
                $uri_controller = ucfirst($uri[0]).".php";
                $path = PHPXI . 'APPLICATION/Controller/' . $uri_controller;
                if(file_exists($path)){
                    $controller = $uri_controller;
                    $controller_name = $uri[0];
                }
              }
              $path = PHPXI . 'APPLICATION/Controller/' . $controller;
              require_once($path);
              
              $class = new $controller_name();
              $default_method = $this->config->item("controller.default_method");
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
              $this->view = ob_get_contents();
              ob_get_clean();

              if($this->config->item("cache.html_compressor")){
                echo preg_replace("/\s+/", " ", $this->view);
              }else{
                echo $this->view;
              }
            }

          }
        }
    }
}

$msure = microtime (); 
$msure = explode (' ', $msure); 
$msure = $msure[1] + $msure[0]; 
define("LOAD_TIME", round (($msure - TIMER_START), 5));
define("MEMORY_USE", round(memory_get_peak_usage()/1048576, 3));