<?php

function base_url($path = ""){
  global $config;
  $return = rtrim($config["base_url"], "/") . '/' . ltrim($path, "/");
  return $return;
}

function site_url($path = "", $echo = true){
  $return = base_url($path, false);
  if($echo){
    echo $return;
  }else{
    return $return;
  }
}

function get_url(){
  if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on"){
    $protocol = "https";
  }else{
    $protocol = "http";
  }
  $url = $protocol.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  return $url;
}

function redirect($url = "", $time = "0"){
  if($url == ""){
    $url = base_url();
  }
  if($time == 0){
    header("Location: ".$url);
  }else{
    header("Refresh:".$time."; url=".$url);
  }
}
