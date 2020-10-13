<?php
namespace PHPXI\SYSTEM;

class Route{

    private $url;

    private $current_controller;
    private $current_cfunction;

    public function url(){
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $basename = basename($_SERVER['SCRIPT_NAME']);
        if(MULTI_LANGUAGES){
            $request_uri = "/".trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
            $uris = explode("/", ltrim($request_uri, "/"));
            unset($uris[0]);
            $this->url = "/".implode("/", $uris);
        }else{
            $this->url = str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
        }
        return $this->url;
    }

    public function autorun(){
        $uri = $this->url();
        if($uri == "/"){
            return $this->run("/", DEFAULT_CONTROLLER_NAME.'@'.DEFAULT_CONTROLLER_FUNCTION, DEFAULT_CONTROLLER_METHOD);
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
            return $this->run($url, $className.'@'.$functionName, DEFAULT_CONTROLLER_METHOD);
        }
    }

    public function notfound($parameters){
        $controller = explode("@", DEFAULT_CONTROLLER_404);
        $controllerFile = PHPXI . '/APPLICATION/Controller/'.ucfirst($controller[0]).'.php';
        if(file_exists($controllerFile)){
            require_once($controllerFile);
            $className = ucfirst($controller[0]);
            $this->current_controller = $className;
            $this->current_cfunction = $controller[1];
            $className = "Controller\\".ucfirst($controller[0]);
            $class = new $className;
            if(method_exists($class, $controller[1])){
                call_user_func_array([$class, $controller[1]], $parameters);
            }else{
                echo 'ERROR : Method ('.$controller[1].') not found in Controller ('.$className.')';
                echo "\n";
            }
        }else{
            echo "<b>".$controllerFile."</b> file not found.\n";
        } 
    }

    public function run($url, $callback, $method = "get"){
        $view = null;
        $request_uri = $this->url;
        if($request_uri == ""){
            $request_uri = $this->url();
            $this->url = $request_uri;
        }
        $methods = explode("|", strtoupper($method));
        if(in_array($_SERVER["REQUEST_METHOD"], $methods)){
            $patterns = array(
                '{string}'  => '([a-zA-Z]+)',
                '{int}'     => '([0-9]+)',
                '{all}'     => '(.*)'
            );
            $url = str_replace(array_keys($patterns), array_values($patterns), $url);
            
            ob_start();
            if(preg_match('@^'.$url.'$@', $request_uri, $parameters)){
                unset($parameters[0]);
                if(is_callable($callback)){
                    call_user_func_array($callback, $parameters);
                }else{
                    $controller = explode("@", $callback);
                    $controllerFile = PHPXI . '/APPLICATION/Controller/'.ucfirst($controller[0]).'.php';
                    if(file_exists($controllerFile)){
                        require_once($controllerFile);
                        $className = ucfirst($controller[0]);
                        $this->current_controller = $className;
                        $this->current_cfunction = $controller[1];
                        define("CURRENT_CPARAMETERS", $parameters);
                        $className = "Controller\\".$className;
                        $class = new $className;
                        if(method_exists($class, $controller[1])){
                            call_user_func_array([$class, $controller[1]], $parameters);
                        }else{
                            if(FORCE_CONTROLLER_FUNCTION != ""){
                                $this->current_cfunction = FORCE_CONTROLLER_FUNCTION;
                                call_user_func_array([$class, FORCE_CONTROLLER_FUNCTION], $parameters);
                            }else{
                                if(ENV == "development"){
                                    $this->notfound(['ERROR : Method ('.$controller[1].') not found in Controller ('.$className.')']);
                                }else{
                                    $this->notfound(["404 : Page Not Found"]);
                                }
                            }
                        }
                    }else{
                        if(FORCE_CONTROLLER_NAME != "" and FORCE_CONTROLLER_FUNCTION != ""){
                            $controllerFile = PHPXI . '/APPLICATION/Controller/'.ucfirst(FORCE_CONTROLLER_NAME).'.php';
                            require_once($controllerFile);
                            $className = "Controller\\".FORCE_CONTROLLER_NAME;
                            $this->current_controller = FORCE_CONTROLLER_NAME;
                            $this->current_cfunction = FORCE_CONTROLLER_FUNCTION;
                            $class = new $className;
                            call_user_func_array([$class, FORCE_CONTROLLER_FUNCTION], $parameters);
                        }else{
                            if(ENV == "development"){
                                $this->notfound(['ERROR : "'.$controllerFile.'" not found.']);
                            }else{
                                $this->notfound(["404 : Page Not Found"]);
                            }
                        }
                    }
                }
                define("CURRENT_CONTROLLER", $this->current_controller);
                define("CURRENT_CFUNCTION", $this->current_cfunction);
            }
            $view = ob_get_clean();
            ob_end_flush();
        }
        return $view;
    }


    
}
