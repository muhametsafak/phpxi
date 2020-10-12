<?php

function base_url($path = ""){
  if(MULTI_LANGUAGES){
    $return = rtrim(BASE_URL, "/") . '/' . CURRENT_LANGUAGE . '/' . ltrim($path, "/");
  }else{
    $return = rtrim(BASE_URL, "/") . '/' . ltrim($path, "/");
  }
  return $return;
}

function public_url($path = "", $echo = true){
  $return = rtrim(BASE_URL, "/") . '/' . ltrim($path, "/");
  if($echo){
    echo $return;
  }else{
    return $return;
  }
}

function site_url($path = "", $echo = true){
  $return = base_url($path, false);
  if($echo){
    echo $return;
  }else{
    return $return;
  }
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

function slug($value){
  $value = strip_tags(trim($value));
  $find = array(' ', '&amp;quot;', '&amp;amp;', '&amp;', '\r\n', '\n', '/', '\\', '+', '<', '>');
  $value = str_replace ($find, '-', $value);
  $find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
  $value = str_replace ($find, 'e', $value);
  $find = array('í', 'ý', 'ì', 'î', 'ï', 'I', 'Ý', 'Í', 'Ì', 'Î', 'Ï','İ','ı');
  $value = str_replace ($find, 'i', $value);
  $find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
  $value = str_replace ($find, 'o', $value);
  $find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
  $value = str_replace ($find, 'a', $value);
  $find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
  $value = str_replace ($find, 'u', $value);
  $find = array('ç', 'Ç');
  $value = str_replace ($find, 'c', $value);
  $find = array('þ', 'Þ','ş','Ş');
  $value = str_replace ($find, 's', $value);
  $find = array('ð', 'Ð','ğ','Ğ');
  $value = str_replace ($find, 'g', $value);
  $find = array('/[^A-Za-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
  $repl = array('', '-', '');
  $value = preg_replace ($find, $repl, $value);
  $value = str_replace ('--', '-', $value);
  $value = strtolower($value);
  return $value;
}
