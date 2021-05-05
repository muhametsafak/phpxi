<?php
namespace Application\Model;

use Config;

class MyOneModel{
    public static function main(){
        echo "Ben MyOneModel'im. " . Config::get("config.timezone");
    }
}
