<?php

$msure = microtime (); 
$msure = explode (' ', $msure ); 
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);


require_once(PHPXI . "APPLICATION/Config/Defined.php");

if(!defined("ENV")){
    define("ENV", "production");
}

if(ENV == "development"){
  ini_set('error_reporting', E_ALL);
  error_reporting(-1);
  ini_set("display_errors", 1);
}else{
  error_reporting(0);
  ini_set("display_errors", 0);
}

require_once(PHPXI . "/SYSTEM/Debug/Debug.php");

require_once(PHPXI . "/SYSTEM/Debug/Reporting.php");


$configs = array();
$phpxi_config_file = array("config", "database", "autoload", "upload", "cache", "cookie");
foreach($phpxi_config_file as $conf){
  $config = array();
  $path = PHPXI . 'APPLICATION/Config/' . ucfirst($conf) . '.php';
  require_once($path);
  foreach($config as $key => $value){
    $configs[$conf][$key] = $value;
  }
  unset($config);
}
unset($phpxi_config_file);

if(isset($configs["autoload"]["config"]) and sizeof($configs["autoload"]["config"]) > 0){
  foreach($configs["autoload"]["config"] as $conf){
    $path = PHPXI . 'APPLICATION/Config/' . ucfirst($conf) . '.php';
    if(file_exists($path)){
      $config = array();
      require_once($path);
      foreach($config as $key => $value){
        $configs[$conf][$key] = $value;
      }
      unset($config);
    }
  }
}
$config = $configs;
unset($configs);

date_default_timezone_set($config['config']['timezone']);

mb_internal_encoding($config['config']['charset']);

require_once(PHPXI . "SYSTEM/Helpers/Object.php");

require_once(PHPXI . "SYSTEM/Helpers/Current.php");

require_once(PHPXI . "SYSTEM/Helpers/Urls.php");

require_once(PHPXI . "SYSTEM/Helpers/Path.php");

require_once(PHPXI . "/SYSTEM/Helpers/Compressor.php");

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
          $db_config = $config["database"][$database];
          $db[$database] = new PHPXI\SYSTEM\MYSQLI\DB($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
      }
  }else{
      $db_config = $config["database"][$config["autoload"]["connect_db"][0]];
      $db = new PHPXI\SYSTEM\MYSQLI\DB($db_config["host"], $db_config["user"], $db_config["password"], $db_config["name"], $db_config["charset"], $db_config["prefix"]);
  }
}

require_once(PHPXI . "/SYSTEM/Hook/Hook.php");

require_once(PHPXI . "/SYSTEM/Upload/Upload.php");

require_once(PHPXI . "/SYSTEM/Route/Route.php");

require_once(PHPXI . "/SYSTEM/Route/Uri.php");

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
    private $route;
    
    function __construct(){
      $this->config = new PHPXI\SYSTEM\Config();
      $this->route = new PHPXI\SYSTEM\Route();
    }

    public function route($url, $callback, $method = "get"){
      $this->view = $this->route->run($url, $callback, $method);
      if($this->view != ""){
        $this->view();
      }
    }

    public function autorun(){
      $this->view = $this->route->autorun();
      if($this->view != ""){
        $this->view();
      }
    }

    public function view(){
      if($this->config->item("cache.status")){
        $this->cache = new PHPXI\SYSTEM\Cache();
        $this->cache->path($this->config->item("cache.path"))->timeout($this->config->item("cache.timeout"))->file("%%".md5(current_url())."%%.html");
        if(!$this->cache->is() || $this->cache->is_timeout()){
          if(HTML_Compressor){
            $this->cache->content(html_compressor($this->view));
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
        if(HTML_Compressor){
          echo html_compressor($this->view);
        }else{
          echo $this->view;
        }
      }
    }

}

$msure = microtime (); 
$msure = explode (' ', $msure); 
$msure = $msure[1] + $msure[0]; 
define("LOAD_TIME", round (($msure - TIMER_START), 5));
define("MEMORY_USE", round(memory_get_peak_usage()/1048576, 3));