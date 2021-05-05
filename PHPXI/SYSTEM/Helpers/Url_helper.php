<?php
use \PHPXI\Libraries\Config\Config as Config;

function base_url($path = ""){
    if(Config::get("language.multi")){
        $return = rtrim(BASE_URL, "/") . '/' . CURRENT_LANGUAGE . '/' . ltrim($path, "/");
    }else{
        $return = rtrim(BASE_URL, "/") . '/' . ltrim($path, "/");
    }
    return $return;
}

function public_url($path = "", $echo = true)
{
    $return = rtrim(BASE_URL, "/") . '/' . ltrim($path, "/");
    if($echo){
        echo $return;
    }else{
        return $return;
    }
}

function site_url($path = "", $echo = true)
{
    $return = base_url($path, false);
    if($echo){
        echo $return;
    }else{
        return $return;
    }
}

function get_referer(){
    if(isset($_SERVER['HTTP_REFERER'])){
        return $_SERVER['HTTP_REFERER'];
    }else{
        return false;
    }
}

function redirect($url = "", $time = "0")
{
    if($url == ""){
        $url = base_url();
    }
    if($time == 0){
        header("Location: ".$url);
    }else{
        header("Refresh:".$time."; url=".$url);
    }
}

function slug($value)
{
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
  

function get_current_url_path()
{
    if(Config::get("language.multi")){
        $request_uri = mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8");
        $request_uri = mb_strtolower($request_uri, "UTF-8");
        $request_uri = "/".trim($request_uri, "/");
        $uris = explode("/", ltrim($request_uri, "/"));
        unset($uris[0]);
        $url = "/".implode("/", $uris);
    }else{
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $basename = basename($_SERVER['SCRIPT_NAME']);
        if($dirname == "/"){
            $request_uri = str_replace($basename, null, $_SERVER["REQUEST_URI"]);
        }else{
            $request_uri = str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
        }
        $url = $request_uri;
    }
    if($url == ""){
        $url = "/";
    }
    return $url;
}
