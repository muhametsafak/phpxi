<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Routing;

use \PHPXI\Libraries\Config\Config as Config;

class Filters
{
    private static $filters = [];

    private static $only_before = [];

    private static $only_after = [];

    private static $run_before = [];

    private static $run_after = [];

    public static $patterns = [
        '{int[0-9]?}'       => '([0-9]+)',
        '{string[0-9]?}'    => '([a-zA-Z0-9-_]+)',
        ':id[0-9]?'         => '([0-9]+)',
        ':str[0-9]?'        => '([a-zA-Z0-9-_]+)',
        ':any'              => '(.*)'
    ];

    private static function filter_load($name)
    {
        if(!in_array($name, self::$filters)){
            $filterName = "\\Application\\Filters\\" . ucfirst($name);
            self::$filters[$name] = new $filterName();
        }
    }

    public static function add(string $name)
    {
        self::add_before($name);
        self::add_after($name);
    }

    public static function add_before(string $name)
    {
        if(!in_array($name, self::$only_before)){
            self::$only_before[] = $name;
        }
    }

    public static function add_after(string $name)
    {
        if(!in_array($name, self::$only_after)){
            self::$only_after[] = $name;
        }
    }

    public static function prepare()
    {
        $global_filters = Config::get("filters.globals.filters");
        if(is_array($global_filters) and sizeof($global_filters) > 0){
            foreach($global_filters as $row){
                self::add($row);
            }
        }
        
        $global_before = Config::get("filters.globals.before");
        if(is_array($global_before) and sizeof($global_before) > 0){
            foreach($global_before as $row){
                self::add_before($row);
            }
        }
        
        $global_after = Config::get("filters.globals.after");
        if(is_array($global_after) and sizeof($global_after) > 0){
            foreach($global_after as $row){
                self::add_after($row);
            }
        }
    }

    public static function route_filter($filters)
    {
        if(is_array($filters) and sizeof($filters) > 0){
            foreach($filters as $row){
                self::add($filters);
            }
        }
        if(is_string($filters) and $filters != ""){
            self::add($filters);
        }
    }

    public static function global_route_filter($url)
    {
        $filters = Config::get("filters.filters");
        if(is_array($filters) and sizeof($filters) > 0){
            foreach($filters as $filterName => $apply){

                if(isset($apply['before']) and is_array($apply['before']) and sizeof($apply['before']) > 0){
                    foreach($apply['before'] as $path){
                        foreach(self::$patterns as $key => $value){
                            $path = preg_replace('#' . $key . '#', $value, $path);
                        }
                        $pattern = '#^' . $path . '$#';
                        if(preg_match($pattern, $url, $params)){
                            self::add_before($filterName);
                        }
                    }
                }

                if(isset($apply['after']) and is_array($apply['after']) and sizeof($apply['after']) > 0){
                    foreach($apply['after'] as $path){
                        foreach(self::$patterns as $key => $value){
                            $path = preg_replace('#' . $key . '#', $value, $path);
                        }
                        $pattern = '#^' . $path . '$#';
                        if(preg_match($pattern, $url, $params)){
                            self::add_after($filterName);
                        }
                    }
                }

            }
        }
    }

    public static function before()
    {
        if(is_array(self::$only_before) and sizeof(self::$only_before) > 0){
            foreach(self::$only_before as $row){
                self::filter_load($row);
                if(!in_array($row, self::$run_before)){
                    if(self::$filters[$row]->before() === false){
                        return false;
                    }
                    self::$run_before[] = $row;
                }
            }
        }
        return true;
    }

    public static function after()
    {
        if(is_array(self::$only_after) and sizeof(self::$only_after) > 0){
            foreach(self::$only_after as $row){
                self::filter_load($row);
                if(!in_array($row, self::$run_after)){
                    if(self::$filters[$row]->after() === false){
                        return false;
                    }
                    self::$run_after[] = $row;
                }
            }
        }
        return true;
    }


}
