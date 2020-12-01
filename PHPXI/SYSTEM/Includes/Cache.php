<?php
namespace PHPXI;

class Cache{

    private string $cache_file;
    private int $timeout = 86400;
    private string $content;
    private string $cache_path;
    private string $fullpath;
    private bool $gzip_compressor = false;

    function __construct(){
        $this->cache_path = PHPXI . '/APPLICATION/Cache/';
    }

    public function path(string $path){
        $this->cache_path = $path;
        $this->fullpath = $this->cache_path . '/' . $this->cache_file;
        return $this;
    }

    public function timeout(int $second = 86400){
        $this->timeout = $second;
        return $this;
    }

    public function content(string $content = ""){
        $this->content = $content;
        return $this;
    }

    public function file(string $file){
        $this->cache_file = $file;
        $this->fullpath = $this->cache_path . '/' . $this->cache_file;
        return $this;
    }

    public function gzip(bool $gzip){
        $this->gzip_compressor = $gzip;
        return $this;
    }

    public function cache(): string{
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

    public function create(): string{
        touch($this->fullpath);
        $open = fopen($this->fullpath, "w+");
        fwrite($open, $this->content);
        fclose($open);
        return $this->content;
    }

    public function is(): bool{
        if(file_exists($this->fullpath)){
            return true;
        }else{
            return false;
        }
    }

    public function is_timeout(): bool{
        if((time() - filemtime($this->fullpath)) > $this->timeout){
            return true;
        }else{
            return false;
        }
    }

    public function read(): string{
        $open = fopen($this->fullpath, "r");
        $this->content = fread($open, filesize($this->fullpath));
        fclose($open);
        return $this->content;
    }

    public function write(): string{
        $open = fopen($this->fullpath, "w+");
        fwrite($open, $this->content);
        fclose($open);
        return $this->content;
    }

}
