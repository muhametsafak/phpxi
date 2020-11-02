<?php
$xi_memory_use_starting = memory_get_usage();
$msure = microtime (); 
$msure = explode (' ', $msure ); 
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);

if(!defined("SYSTEM")){
  define("SYSTEM", PHPXI . "SYSTEM/");
}

if(!defined("APP")){
  define("APP", PHPXI . "APPLICATION/");
}

if(!defined("VERSION")){
  define("VERSION", "1.4");
}

require_once(SYSTEM . "Autoload.php");

require_once(APP . "Config/Defined.php");

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

if(!function_exists("PHPXI_ShutdownHandler")){
  function PHPXI_ShutdownHandler(){
    if(@is_array($error = @error_get_last())){
        return(@call_user_func_array('PHPXI_ErrorHandler', $error));
    };
    return(TRUE);
  };
}
register_shutdown_function('PHPXI_ShutdownHandler');
if(!function_exists("PHPXI_ErrorHandler")){
  function PHPXI_ErrorHandler($type, $message, $file, $line){
    switch(ENV){
        case "development" :
            $debug = new PHPXI\Debug($type, $file, $line, $message);
            return die($debug->development());
        break;
        default : return false;
    }
  }
}
$old_error_handler = set_error_handler("PHPXI_ErrorHandler");


$configs = array();
$phpxi_config_file = array("config", "database", "autoload", "upload", "cache", "cookie", "session", "route");
foreach($phpxi_config_file as $conf){
  $config = array();
  $path = APP . 'Config/' . ucfirst($conf) . '.php';
  require_once($path);
  foreach($config as $key => $value){
    $configs[$conf][$key] = $value;
  }
  unset($config);
}
unset($phpxi_config_file);

if(isset($configs["autoload"]["config"]) and sizeof($configs["autoload"]["config"]) > 0){
  foreach($configs["autoload"]["config"] as $conf){
    $path = APP . 'Config/' . ucfirst($conf) . '.php';
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

$config = new PHPXI\Config();

if($config->item("config.timezone")){
  date_default_timezone_set($config->item("config.timezone"));
}
if($config->item("config.charset")){
  mb_internal_encoding($config->item("config.charset"));
}

$benchmark = new PHPXI\Benchmark();

$cache = new PHPXI\Cache();

$cookie = new PHPXI\Cookie();

$session = null;
if($config->item("session.start")){
  if(file_exists($config->item("session.path"))){
    session_save_path($config->item("session.path"));
  }
  session_start();
  $session = new PHPXI\Session();
}

$file = new PHPXI\File();

$form = new PHPXI\Form();

$hook = new PHPXI\Hook();

$http = new PHPXI\Http();

$input = new PHPXI\Input();

$lang = new PHPXI\Language();

$server = new PHPXI\Server();

$upload = null;
if($config->item("autoload.upload")){
  $upload = new PHPXI\Upload();
  $upload->config($config->item("upload"));
}

$uri = new PHPXI\Uri();

$phpxi_helper_file = array("object", "current", "urls", "path", "compressor", "database");
foreach($phpxi_helper_file as $row){
  $path = SYSTEM . "Helpers/" . ucfirst($row) . ".php";
  if(file_exists($path)){
    require_once($path);
  }
}
unset($phpxi_helper_file);

$application_helper_file = $config->item("autoload.helper");
if(is_array($application_helper_file) and sizeof($application_helper_file) > 0){
  foreach($application_helper_file as $row){
    $path = APP . "Helpers/" . ucfirst($row) . "_helper.php";
    if(file_exists($path)){
      require_once($path);
    }
  }
}
unset($application_helper_file);

require_once(SYSTEM . "Route.php");
$route = new Route\XI_Route();

$db = null;
if($config->item("autoload.db")){
  $connect_db = $config->item("autoload.connect_db");
  $connetions = array();
  if(is_array($connect_db) and sizeof($connect_db) > 0){
    foreach($connect_db as $row){
      if($config->item("database.".$row)){
        $connections[] = $row;
      }
    }
  }
  $connections_size = sizeof($connections);
  if($connections_size > 0){
    if($connections_size == 1){
      $db = db_connect($connections[0]);
    }else{
      $config->set("autoload.conenct_db", $connections);
      foreach($connections as $row){
        $db = new PHPXI\MultiDatabase();
      }
    }
  }
  unset($connect_db);
  unset($connections_size);
  unset($connections);
}

require_once(SYSTEM . "Model.php");

require_once(SYSTEM . "Controller.php");

$models = array();
$application_model_file = $config->item("autoload.model");
if(is_array($application_model_file) and sizeof($application_model_file) > 0){
  foreach($application_model_file as $key => $value){
    $path = APP . 'Model/' . ucfirst($key) . ".php";
    if(file_exists($path)){
      require_once($path);
      $model_name = "Model\\".$key;
      $models[$value] = new $model_name();
      if(method_exists($models[$value], "autoload")){
        $models[$value]->autoload();
      }
    }
  }
}
unset($application_model_file);

$application_libraries_file = $config->item("autoload.libraries");
if(is_array($application_libraries_file) and sizeof($application_libraries_file) > 0){
  foreach($application_libraries_file as $library){
    $path = APP . 'Libraries/' . ucfirst($library) . '.php';
    if(file_exists($path)){
      require_once($path);
    }
  }
}
unset($application_libraries_file);


class PHPXI{
    public $method;
    private $view;
    private $route;
    
    function __construct(){
      global $config, $route;
      $this->config = $config;
      $this->route = $route;
    }

    public function route($url, $callback, $method = "get"){
      $this->view = $this->route->run($url, $callback, $method);
      if($this->view != ""){
        $this->view();
        exit;
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
        global $cache;
        $this->cache = $cache;
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
define("MEMORY_USE", round((memory_get_usage() - $xi_memory_use_starting) / 1048576, 4));
unset($xi_memory_use_starting);
define("MEMORY_USE_MAX", round(memory_get_peak_usage()/1048576, 3));
define("LOAD_TIME", round (($msure - TIMER_START), 5));
