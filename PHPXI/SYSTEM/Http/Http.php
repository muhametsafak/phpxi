<?php
namespace PHPXI\SYSTEM;

class Http{
    private $url;

    private $scheme;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $path;
    private $query;
    private $fragment;

    function __construct(){
        if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on"){
            $scheme = "https";
        }else{
            $scheme = "http";
        }
        $this->url = $scheme.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }

    public function set($url){
        $this->url = $url;
    }

    public function scheme($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_SCHEME);
    }

    public function host($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_HOST);
    }

    public function port($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_PORT);
    }

    public function user($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_USER);
    }

    public function pass($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_PASS);
    }

    public function path($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_PATH);
    }

    public function query($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_QUERY);
    }

    public function fragment($url = ""){
        if($url == ""){
            $url = $this->url;
        }
        return parse_url($url, PHP_URL_FRAGMENT);
    }

}
