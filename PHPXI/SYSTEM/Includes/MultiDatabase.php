<?php
namespace PHPXI;

class MultiDatabase{

    function __construct(){
        global $config;
        
        if($config->item("autoload.db")){
            foreach($config->item("autoload.connect_db") as $db){
                $this->$db = db_connect($db);
            }
        }

    }

}
