<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Cache;

class Cache
{

    private static $cache_file = WEIGHT_PATH . 'Cache/';
    private static $timeout = 86400;
    private static $content;
    private static $cache_path;
    private static $fullpath;
    private static $gzip_compressor = false;

    public static function path($path)
    {
        self::$cache_path = $path;
        self::$fullpath = self::$cache_path . '/' . self::$cache_file;
        
    }

    public static function timeout($second = 86400)
    {
        self::$timeout = $second;
    }

    public static function content($content = "")
    {
        self::$content = $content;
    }

    public static function file($file)
    {
        self::$cache_file = $file;
        self::$fullpath = self::$cache_path . '/' . self::$cache_file;
    }

    public static function gzip($gzip)
    {
        self::$gzip_compressor = $gzip;
    }

    public static function cache()
    {
        if(self::is()){
            if(self::is_timeout()){
                return self::write();
            }else{
                return self::read();
            }
        }else{
            self::create();
        }
        return self::$content;
    }

    public static function create()
    {
        touch(self::$fullpath);
        $open = fopen(self::$fullpath, "w+");
        fwrite($open, self::$content);
        fclose($open);
        return self::$content;
    }

    public static function is()
    {
        if(file_exists(self::$fullpath)){
            return true;
        }else{
            return false;
        }
    }

    public static function is_timeout()
    {
        if((time() - filemtime(self::$fullpath)) > self::$timeout){
            return true;
        }else{
            return false;
        }
    }

    public static function read()
    {
        $open = fopen(self::$fullpath, "r");
        self::$content = fread($open, filesize(self::$fullpath));
        fclose($open);
        return self::$content;
    }

    public static function write()
    {
        $open = fopen(self::$fullpath, "w+");
        fwrite($open, self::$content);
        fclose($open);
        return self::$content;
    }

}
