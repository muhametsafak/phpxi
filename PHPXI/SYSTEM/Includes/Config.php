<?php
namespace PHPXI;

class Config{
    
    public $config;
    
    function __construct(){
        global $configs;
        $this->config = $configs;
    }
        
    
    /**
     * item
     *
     * @param  mixed $key
     * @param  mixed $type = if true @return object
     * @return void
     */
    public function item($key, $type = false){
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

}