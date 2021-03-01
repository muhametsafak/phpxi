<?php
namespace PHPXI;

class Cache{

    private $cache_file;
    private $timeout = 86400;
    private $content;
    private $cache_path;
    private $fullpath;
    private $gzip_compressor = false;

    function __construct(){
        $this->cache_path = PHPXI . '/APPLICATION/Cache/';
    }

    public function path($path){
        $this->cache_path = $path;
        $this->fullpath = $this->cache_path . '/' . $this->cache_file;
        return $this;
    }

    public function timeout($second = 86400){
        $this->timeout = $second;
        return $this;
    }

    public function content($content = ""){
        $this->content = $content;
        return $this;
    }

    public function file($file){
        $this->cache_file = $file;
        $this->fullpath = $this->cache_path . '/' . $this->cache_file;
        return $this;
    }

    public function gzip($gzip){
        $this->gzip_compressor = $gzip;
        return $this;
    }

    public function cache(){
        if($this->is()){
            if($this->is_timeout()){
                return $this->write();
            }else{
                return $this->read();
            }
        }else{
            $this->create();
        }
        return $this->content;
    }

    public function create(){
        touch($this->fullpath);
        $open = fopen($this->fullpath, "w+");
        fwrite($open, $this->content);
        fclose($open);
        return $this->content;
    }

    public function is(){
        if(file_exists($this->fullpath)){
            return true;
        }else{
            return false;
        }
    }

    public function is_timeout(){
        if((time() - filemtime($this->fullpath)) > $this->timeout){
            return true;
        }else{
            return false;
        }
    }

    public function read(){
        $open = fopen($this->fullpath, "r");
        $this->content = fread($open, filesize($this->fullpath));
        fclose($open);
        return $this->content;
    }

    public function write(){
        $open = fopen($this->fullpath, "w+");
        fwrite($open, $this->content);
        fclose($open);
        return $this->content;
    }

}
