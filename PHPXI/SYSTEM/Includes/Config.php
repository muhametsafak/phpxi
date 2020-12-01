<?php
namespace PHPXI;

class Config{
    
    public $config;
    
    function __construct(){
        global $configs;
        $this->config = $configs;
    }

    public function item(string $key, bool $type = false){
        $ids = explode(".", $key);
        if(sizeof($ids) > 1){
            if(isset($this->config[$ids[0]])){
                $return = $this->config[$ids[0]];
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
            if(isset($this->config[$key])){
                $return = $this->config[$key];
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

    public function set(string $key, $value): bool{
        $ids = explode(".", $key);
        if(sizeof($ids) == 1){
            $this->config[$key] = $value;
            return true;
        }elseif(sizeof($ids) == 2){
            $this->config[$ids[0]][$ids[1]] = $value;
            return true;
        }else{
            return false;
        }
    }

}