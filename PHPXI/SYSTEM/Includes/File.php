<?php
namespace PHPXI;

class File{
    public string $path;

    private string $file;

    public function load(string $path){
        if($this->exists($path)){
            $this->path = $path;
        }
        return $this;
    }

    public function exists(string $path = ""): bool{
        if($path == ""){
            $path = $this->path;
        }
        if(file_exists($path)){
            return true;
        }else{
            return false;
        }
    }
    
    public function read(string $path = ""){
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

    public function empty(string $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return $this->rewrite("", $path);
        }else{
            return false;
        }
    }

    public function write(string $string = "", string $path = ""): bool{
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

    public function rewrite(string $string = "", string $path = ""): bool{
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

    public function time(string $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return filemtime($path);
        }else{
            return false;
        }
    }

    public function size(string $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return filesize($path);
        }else{
            return false;
        }
    }

    public function mime(string $path = ""){
        if($path == ""){
            $path = $this->path;
        }
        if($this->exists($path)){
            return mime_content_type($path);
        }else{
            return false;
        }
    }

    public function copy(string $copy_path, string $file_path = ""): bool{
        if($file_path == ""){
            $file_path = $this->path;
        }
        if(copy($file_path, $copy_path)){
            return true;
        }else{
            return false;
        }
    }

    public function rename(string $new_path, string $path = ""): bool{
        if($path == ""){
            $path = $this->path;
        }
        if(rename($path, $new_path)){
            return true;
        }else{
            return false;
        }
    }

    public function move(string $new_path, string $path = ""): bool{
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
