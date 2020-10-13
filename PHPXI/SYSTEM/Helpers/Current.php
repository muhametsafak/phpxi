<?php

function current_url(){
    if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on"){
      $protocol = "https";
    }else{
      $protocol = "http";
    }
    $url = $protocol.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    return $url;
}
define("CURRENT_URL", current_url());

function current_language(){
    $return = DEFAULT_LANGUAGE;
    if(MULTI_LANGUAGES){
        $request_uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
        if($request_uri != ""){
          $uris = explode("/", ltrim($request_uri, "/"));
          $return = $uris[0];
        }
    }
    return $return;
}
define("CURRENT_LANGUAGE", current_language());