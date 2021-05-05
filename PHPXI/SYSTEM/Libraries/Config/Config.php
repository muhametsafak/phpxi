<?php 
namespace PHPXI\Libraries\Config;

use \PHPXI\Libraries\Debugging\Logger as Logger;

class Config{

    private static $application_config_file = [
        "autoload",
        "cache",
        "config",
        "cookie",
        "database",
        "filters",
        "language",
        "session",
        "upload"
    ];

    private static $config = [];

    public static function main(){
        Logger::system("Config dosyaları çağırılıyor...");
            
        foreach(self::$application_config_file as $file){
            self::load($file);
        }

        $autoload_config = self::get("autoload.config");
        if(is_array($autoload_config) and sizeof($autoload_config) > 0){
            foreach ($autoload_config as $row) {
                self::load($row);
            }
        }
        
        Logger::system("Config dosyaları çekildi ve yükledi.");
    }

    protected static function load($fileName)
    {
        $path = APPLICATION_PATH . 'Config/' . ucfirst($fileName) . '.php';
        if(file_exists($path) || DEVELOPMENT){
            Logger::system("Config/".$fileName.".php yükleniyor...");
            $config = [];
            require $path;
            foreach($config as $key => $value){
                self::$config[$fileName][$key] = $value;
            }
        }
    }

    public static function get($key, $type = false){
        $ids = explode(".", $key);
        if(sizeof($ids) > 1){
            if(isset(self::$config[$ids[0]])){
                $return = self::$config[$ids[0]];
                unset($ids[0]);
                foreach($ids as $id){
                    if(isset($return[$id])){
                        $return = $return[$id];
                    }else{
                        $return = false;
                    }
                }
            }
        }else{
            if(isset(self::$config[$key])){
                $return = self::$config[$key];
            }
        }
        if(isset($return)){
            if($type and is_array($return)){
                $return = arrayObject($return);
            }
        }else{
            $return = false;
        }
        return $return;
    }

    public static function set($key, $value){
        $ids = explode(".", $key);
        if(sizeof($ids) == 1){
            self::$config[$key] = $value;
            return true;
        }elseif(sizeof($ids) == 2){
            self::$config[$ids[0]][$ids[1]] = $value;
            return true;
        }else{
            return false;
        }
    }


}