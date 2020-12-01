<?php
namespace PHPXI;

class Benchmark{

    public function start(string $bench){
        $mtime = microtime(); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0];
        $this->$bench = $mtime;
    }

    public function stop(string $bench, int $precision = 5){
        $mtime = microtime (); 
        $mtime = explode (' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        return round (($mtime - $this->$bench), $precision);
    }

}