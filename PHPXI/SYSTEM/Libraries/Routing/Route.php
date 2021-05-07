<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Routing;

use \PHPXI\Libraries\Routing\Filters as Filters;

class Route{

    public static $routes = [];
    
    public static $prefix = '';

    public static $hasRoute = false;

    public static $current = [
        "controller"    => "",
        "cfunction"     => "",
        "params"        => []
    ];

    public static $patterns = [
        '{int[0-9]?}'       => '([0-9]+)',
        '{string[0-9]?}'    => '([a-zA-Z0-9-_]+)',
        ':id[0-9]?'         => '([0-9]+)',
        ':str[0-9]?'        => '([a-zA-Z0-9-_]+)',
        ':any'              => '(.*)'
    ];

    private static $uri = [];

    public static function get($path, $callback, $parameters = [])
    {
        self::$routes['get'][$path] = [
            'callback' => $callback,
            'parameters' => $parameters
        ];
    }
    
    public static function post($path, $callback, $parameters = [])
    {
        self::$routes['post'][$path] = [
            'callback' => $callback,
            'parameters' => $parameters
        ];
    }

    public static function head($path, $callback, $parameters = [])
    {
        self::$routes['head'][$path] = [
            'callback' => $callback,
            'parameters' => $parameters
        ];
    }

    public static function put($path, $callback, $parameters = [])
    {
        self::$routes['put'][$path] = [
            'callback' => $callback,
            'parameters' => $parameters
        ];
    }

    public static function any($path, $callback, $parameters = [])
    {
        self::get($path, $callback, $parameters);
        self::post($path, $callback, $parameters);
        self::head($path, $callback, $parameters);
        self::put($path, $callback, $parameters);
    }

    public static function uri(int $id){
        if($id >= 0 and isset(self::$uri[$id])){
            return self::$uri[$id];
        }else{
            return false;
        }
    }
    
    public static function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function name($name)
    {
        $key = array_key_last(self::$routes['get']);
        self::$routes['get'][$key]['name'] = $name;
    }
    
    public static function url($name, $params = [])
    {
        $route = array_key_first(array_filter(self::$routes['get'], function($route) use($name){
            return $route['name'] === $name;
        }));
        return str_replace(array_keys($params), array_values($params), $route);
    }

    public static function prefix($prefix)
    {
        self::$prefix = $prefix;
        return new self();
    }

    public static function group(closure $closure)
    {
        $closure();
        self::$prefix = '';
    }

    public static function where($key, $pattern)
    {
        self::$patterns[':' . $key] = '(' . $pattern . ')';
    }

    public static function redirect($from, $to, $status = 301)
    {
        self::$routes['get'][$from] = [
            'redirect'  => $to,
            'status'    => $status
        ];
    }

    public static function to($to, $status = 301)
    {
        header("Location: ".$to, true, $status);
    }

    public static function dispatch()
    {
        $executive = true;
        $method = self::getMethod();
        $url = get_current_url_path();

        $uri = trim($url, "/");
        if($uri != ""){
            self::$uri = explode("/", $uri);
        }

        foreach(self::$routes[$method] as $path => $probs){
            foreach(self::$patterns as $key => $value){
                $path = preg_replace('#' . $key . '#', $value, $path);
            }
            $path = self::$prefix . $path;
            $pattern = '#^' . $path . '$#';
            if(preg_match($pattern, $url, $params)){

                Filters::prepare();
                Filters::global_route_filter($url);
                if(isset($probs["parameters"]["filter"])){
                    Filters::route_filter($probs["parameters"]["filter"]);
                }
                $executive = Filters::before();

                array_shift($params);
                self::$hasRoute = true;
                if($executive){
                    if(isset($probs['redirect'])){
                        self::to($probs['redirect'], $probs['status']);
                    }else{
                        $callback = $probs['callback'];
                        if(is_string($callback)){
                            [$controllerName, $methodName] = explode('@', $callback);
                            $controllerFilePath = APPLICATION_PATH . 'Controller/' . ucfirst($controllerName) . '.php';
                            if(file_exists($controllerFilePath) || DEVELOPMENT){
                                self::$current = [
                                    "controller"    => $controllerName,
                                    "cfunction"     => $methodName,
                                    "params"        => $params
                                ];
                                $controllerName = '\\Application\\Controller\\'.$controllerName;
                                $controller = new $controllerName();
                                if(!method_exists($controller, $methodName)){
                                    self::$hasRoute = false;
                                }
                            }else{
                                self::$hasRoute = false;
                            }
                            if(self::$hasRoute){
                                call_user_func_array([$controller, $methodName], $params);
                            }
                        }elseif(is_callable($callback)){
                            call_user_func_array($callback, $params);
                            self::$current = [
                                "controller"    => '',
                                "cfunction"     => '',
                                "params"        => $params
                            ];
                        }
                    }
                }

                Filters::after();

            }
        }
        if(self::$hasRoute === false){
            self::hasRoute();
        }
        self::route_current_define();
    }

    public static function route_current_define(){
        define("CURRENT_CONTROLLER", self::$current['controller']);
        define("CURRENT_CFUNCTION", self::$current['cfunction']);
        define("CURRENT_CPARAMETERS", self::$current['params']);
    }


    public static function hasRoute(){
        $params = [];
        self::$current = [
            "controller"    => 'Error',
            "cfunction"     => 'error_404',
            "params"        => $params
        ];
        $methodName = 'error_404';
        $controller = new \Application\Controller\Error();
        call_user_func_array([$controller, $methodName], $params);

    }


}
