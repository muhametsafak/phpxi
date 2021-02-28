<?php
namespace Route;

class XI_Route{

    public array $routes = [];
    
    public string $prefix = '';

    public bool $hasRoute = false;

    private string $url = '';

    private string $current_controller = '';

    private string $current_cfunction = '';

    public array $patterns = [
        '{int[0-9]?}'       => '([0-9]+)',
        '{string[0-9]?}'    => '([a-zA-Z0-9-_]+)',
        ':id[0-9]?'         => '([0-9]+)',
        ':str[0-9]?'        => '([a-zA-Z0-9-_]+)'
    ];

    public function route($path, $callback, string $method = 'get'): void{
        $this->routes[$method][$path] = [
            'callback' => $callback
        ];
    }

    public function getUrl(): string{
        if(MULTI_LANGUAGES){
            $request_uri = "/".trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
            $uris = explode("/", ltrim($request_uri, "/"));
            unset($uris[0]);
            $this->url = "/".implode("/", $uris);
        }else{
            $dirname = dirname($_SERVER['SCRIPT_NAME']);
            $basename = basename($_SERVER['SCRIPT_NAME']);
            if($dirname == "/"){
                $request_uri = str_replace($basename, null, $_SERVER["REQUEST_URI"]);
            }else{
                $request_uri = str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
            }
            $this->url = $request_uri;
        }
        if($this->url == ""){
            $this->url = "/";
        }
        return $this->url;
    }

    public function getMethod(): string{
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    public function name(string $name){
        $key = array_key_last($this->routes['get']);
        $this->routes['get'][$key]['name'] = $name;
    }
    
    public function url(string $name, array $params = []): string
    {
        $route = array_key_first(array_filter($this->routes['get'], function($route) use($name){
            return $route['name'] === $name;
        }));
        return str_replace(array_keys($params), array_values($params), $route);
    }

    public function prefix($prefix): Route
    {
        $this->prefix = $prefix;
        return new self();
    }

    public function group(closure $closure): void
    {
        $closure();
        $this->prefix = '';
    }

    public function where(string $key, string $pattern): void{
        $this->patterns[':' . $key] = '(' . $pattern . ')';
    }

    public function redirect(string $from, string $to, int $status = 301): void{
        $this->routes['get'][$from] = [
            'redirect'  => $to,
            'status'    => $status
        ];
    }

    public function to(string $to, int $status = 301): void{
        header("Location: ".$to, true, $status);
    }

    public function dispatch(): string{
        $method = $this->getMethod();
        $url = $this->getUrl();
        ob_start();
        foreach($this->routes[$method] as $path => $probs){
            foreach($this->patterns as $key => $value){
                $path = preg_replace('#' . $key . '#', $value, $path);
            }
            $path = $this->prefix . $path;
            $pattern = '#^' . $path . '$#';
            if(preg_match($pattern, $url, $params)){
                array_shift($params);
                $this->hasRoute = true;
                if(isset($probs['redirect'])){
                    $this->to($probs['redirect'], $probs['status']);
                }else{
                    $callback = $probs['callback'];
                    if(is_string($callback)){
                        [$controllerName, $methodName] = explode('@', $callback);
                        $controllerFilePath = APP . 'Controller/' . ucfirst($controllerName) . '.php';
                        if(file_exists($controllerFilePath)){
                            require APP . 'Controller/'.$controllerName.'.php';
                            $this->current_controller = $controllerName;
                            $controllerName = 'Controller\\'.$controllerName;
                        }else{
                            if(FORCE_CONTROLLER_NAME != "" and FORCE_CONTROLLER_FUNCTION != ""){
                                $controllerFilePath = APP . 'Controller/' . ucfirst(FORCE_CONTROLLER_NAME) . '.php';
                                if(file_exists($controllerFilePath)){
                                    $controllerName = FORCE_CONTROLLER_NAME;
                                    require APP . 'Controller/'.$controllerName.'.php';
                                    $this->current_controller = $controllerName;
                                    $controllerName = 'Controller\\'.$controllerName;
                                    $methodName = FORCE_CONTROLLER_FUNCTION;
                                }
                            }
                        }
                        $controller = new $controllerName();
                        if(!method_exists($controller, $methodName)){
                            $methodName = "index";
                        }
                        $this->current_cfunction = $methodName;
                        define("CURRENT_CONTROLLER", $this->current_controller);
                        define("CURRENT_CFUNCTION", $this->current_cfunction);

                        call_user_func_array([$controller, $methodName], $params);
                    }elseif(is_callable($callback)){
                        call_user_func_array($callback, $params);
                    }
                    define("CURRENT_CPARAMETERS", $params);
                }
            }
        }
        if($this->hasRoute === false){
            $this->hasRoute();
        }
        $view = ob_get_clean();
        if(ob_get_length() > 0){
            ob_end_flush();
        }
        
        return $view;
    }



    public function hasRoute(){
        $params = [];
        if(DEFAULT_CONTROLLER_404 != ""){
            $parse = explode("@", DEFAULT_CONTROLLER_404);
            $controllerName = $parse[0];
            $methodName = $parse[1];
            $this->current_controller = $controllerName;
            $this->current_cfunction = $methodName;
            define("CURRENT_CONTROLLER", $this->current_controller);
            define("CURRENT_CFUNCTION", $this->current_cfunction);
            define("CURRENT_CPARAMETERS", []);
            $controllerFilePath = APP . 'Controller/' . ucfirst($controllerName) . '.php';
            if(file_exists($controllerFilePath)){
                require APP . 'Controller/'.$controllerName.'.php';
                $controllerName = 'Controller\\'.$controllerName;
                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $params);
            }
        }else{
            die("Found not page");
        }
    }


    public function autorun(){
        $uri = $this->getUrl();
        if($uri == "/"){
            return $this->route('/', DEFAULT_CONTROLLER_NAME.'@'.DEFAULT_CONTROLLER_FUNCTION);
        }else{
            $parse = explode("/", trim($uri, "/"));
            $className = $parse[0];
            if(isset($parse[1]) and trim($parse[1]) != ""){
                $functionName = $parse[1];
                $parameters_size = sizeof($parse) - 2;
            }else{
                $functionName = DEFAULT_CONTROLLER_FUNCTION;
                $parameters_size = sizeof($parse) - 1;
                $uri .= '/'.DEFAULT_CONTROLLER_FUNCTION;
                $this->url = $uri;
            }
            $url = "/".$className."/".$functionName.str_repeat("/{all}", $parameters_size);
            return $this->route($url, $className.'@'.$functionName);
        }
    }

}
