<?php
namespace PHPXI\SYSTEM;

class File{
    public $path;

    private $file;
    private $filesize;

    public function load($path){
        if($this->exists($path)){
            $this->path = $path;
        }
    }

    public function exists($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if(file_exists($path)){
            return true;
        }else{
            return false;
        }
    }
    
    public function read($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            $this->path = $path;
            $this->filesize = filesize($this->path);
            $this->file = fopen($this->path);
            $return = fread($this->file, $this->filesize);
            fclose($this->file);
            return $return;
        }else{
            return false;
        }
    }

    public function empty($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return $this->write("", $path);
        }else{
            return false;
        }
    }

    public function write($string = "", $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            $this->file = fopen($path, 'w');
            if(fwrite($path, $string) !== FALSE){
                return true;
            }else{ 
                return false;
            }
        }else{
            return false;
        }
    }

}
