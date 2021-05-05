<?php
namespace PHPXI\Libraries\File;

class File{
    public static $path;

    private static $file;

    public static function load($path)
    {
        if(self::exists($path)){
            self::$path = $path;
        }
    }

    public static function exists($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(file_exists($path)){
            return true;
        }else{
            return false;
        }
    }
    
    public static function read($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            self::$path = $path;
            self::$filesize = filesize(self::$path);
            self::$file = fopen(self::$path);
            $return = fread(self::$file, self::size());
            fclose(self::$file);
            return $return;
        }else{
            return false;
        }
    }

    public static function empty($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            return self::rewrite("", $path);
        }else{
            return false;
        }
    }

    public static function write($string = "", $path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            self::$file = fopen($path, 'a');
            if(fwrite($path, $string) !== FALSE){
                return true;
            }else{ 
                return false;
            }
        }else{
            return false;
        }
    }

    public static function rewrite($string = "", $path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            self::$file = fopen($path, 'w+');
            if(fwrite($path, $string) !== FALSE){
                return true;
            }else{ 
                return false;
            }
        }else{
            return false;
        }
    }

    public static function time($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            return filemtime($path);
        }else{
            return false;
        }
    }

    public static function size($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            return filesize($path);
        }else{
            return false;
        }
    }

    public static function mime($path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(self::exists($path)){
            return mime_content_type($path);
        }else{
            return false;
        }
    }

    public static function copy($copy_path, $file_path = "")
    {
        if($file_path == ""){
            $file_path = self::$path;
        }
        if(copy($file_path, $copy_path)){
            return true;
        }else{
            return false;
        }
    }

    public static function rename($new_path, $path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(rename($path, $new_path)){
            return true;
        }else{
            return false;
        }
    }

    public static function move($new_path, $path = "")
    {
        if($path == ""){
            $path = self::$path;
        }
        if(rename($path, $new_path)){
            return true;
        }else{
            return false;
        }
    }

}
