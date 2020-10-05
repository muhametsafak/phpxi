<?php
namespace PHPXI\SYSTEM;

class File{
    public $path;

    private $file;

    public function load($path){
        if($this->exists($path)){
            $this->path = $path;
        }
        return $this;
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
            $return = fread($this->file, $this->size());
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
            return $this->rewrite("", $path);
        }else{
            return false;
        }
    }

    public function write($string = "", $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            $this->file = fopen($path, 'a');
            if(fwrite($path, $string) !== FALSE){
                return true;
            }else{ 
                return false;
            }
        }else{
            return false;
        }
    }

    public function rewrite($string = "", $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            $this->file = fopen($path, 'w+');
            if(fwrite($path, $string) !== FALSE){
                return true;
            }else{ 
                return false;
            }
        }else{
            return false;
        }
    }

    public function time($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return filemtime($path);
        }else{
            return false;
        }
    }

    public function size($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return filesize($path);
        }else{
            return false;
        }
    }

    public function mime($path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return mime_content_type($path);
        }else{
            return false;
        }
    }

    public function copy($copy_path, $file_path = ""){
        if($file_path == ""){
            $file_path = $this->path;
        }
        if(copy($file_path, $copy_path)){
            return true;
        }else{
            return false;
        }
    }

    public function rename($new_path, $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if(rename($path, $new_path)){
            return true;
        }else{
            return false;
        }
    }

    public function move($new_path, $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if(rename($path, $new_path)){
            return true;
        }else{
            return false;
        }
    }

}
