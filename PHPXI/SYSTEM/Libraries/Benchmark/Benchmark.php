<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Benchmark;

class Benchmark
{

    private static $bench = [];

    public static function start($bench_name)
    {
        $mtime = microtime(); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0];
        self::$bench[$bench_name] = $mtime;
    }

    public static function stop($bench_name, $precision = 5)
    {
        $mtime = microtime (); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        return round (($mtime - self::$bench[$bench_name]), $precision);
    }

}