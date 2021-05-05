<?php
namespace PHPXI\Libraries\Http;

class Http{
    private static $url;

    private static $parse;

    private static $scheme;
    private static $host;
    private static $port;
    private static $user;
    private static $pass;
    private static $path;
    private static $query;
    private static $fragment;

    public static function autoload(){
        if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on"){
            $scheme = "https";
        }else{
            $scheme = "http";
        }
        self::$url = $scheme.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        self::$parse = parse_url(self::$url);
    }

    public static function set($url){
        self::$url = $url;
    }

    public static function scheme($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_SCHEME);
    }

    public static function host($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_HOST);
    }

    public static function port($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_PORT);
    }

    public static function user($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_USER);
    }

    public static function pass($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_PASS);
    }

    public static function path($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_PATH);
    }

    public static function query($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_QUERY);
    }

    public static function fragment($url = ""){
        if($url == ""){
            $url = self::$url;
        }
        return parse_url($url, PHP_URL_FRAGMENT);
    }

    public static function response($code = 200){
        switch ($code) {
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:
                exit('Unknown http status code "' . htmlentities($code) . '"');
            break;
        }
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $code . ' ' . $text);
    }

    public static function userAgent(){
        return $_SERVER['HTTP_USER_AGENT'];
    }


}
