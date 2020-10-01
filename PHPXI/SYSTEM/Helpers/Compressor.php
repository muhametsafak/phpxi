<?php

function str_compressor($str){
    return preg_replace("/\s+/", ' ', $str);
}

function html_compressor($source){
    $source = preg_replace('#^\s*//.+$#m', null, $source); 
    $source = str_compressor($str);
    $source = preg_replace('/<!--(.|\s)*?-->/', null, $source); 
    $source = trim($source); 
    return $source;
}
