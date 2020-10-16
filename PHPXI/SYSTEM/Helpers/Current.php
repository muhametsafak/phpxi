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

if(!function_exists("cpu_use")){
  function cpu_use(){
    $xi_server_cpu_usage = false;
    if(function_exists("sys_getloadavg")){
      $sys_getloadavg = sys_getloadavg();
      $xi_server_cpu_usage = $sys_getloadavg[0];
    }else{
      if(function_exists("exec") and mb_strtolower(PHP_OS_FAMILY) == "windows"){
        exec("wmic cpu get loadpercentage", $output, $value);
        if($value == 0){
          $xi_server_cpu_usage = $output[1];
        }
      }
    }
    return $xi_server_cpu_usage;
  }
}