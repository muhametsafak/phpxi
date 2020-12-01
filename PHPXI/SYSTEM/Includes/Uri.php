<?php
namespace PHPXI;

class Uri{

    public array $uri = [];
    private string $request_uri;
    

    function __construct(){
        
        if(MULTI_LANGUAGES){
            $request_uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
            $uris = explode("/", ltrim($request_uri, "/"));
            unset($uris[0]);
            $request_uri = "/".implode("/", $uris);
        }else{
            $request_uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
        }
        if(isset($request_uri) and trim($request_uri) != ""){
            $this->request_uri = $request_uri;
            foreach(array_filter(explode('/', $request_uri)) as $row){
                $this->uri[] = $row;
            }
        }
    }
    
    public function get(int $id): string{
        if(isset($this->uri[$id])){
            return $this->uri[$id];
        }else{
            return false;
        }
    }

    public function request_uri(): string{
        return $this->request_uri;
    }
    
}
