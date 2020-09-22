<?php
namespace PHPXI\SYSTEM;

class Benchmark{

    public function start($bench){
        $mtime = microtime(); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0];
        $this->$bench = $mtime;
    }

    public function stop($bench, $precision = 5){
        $mtime = microtime (); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        return round (($mtime - $this->$bench), $precision);
    }

}