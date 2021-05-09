<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

if (!function_exists("str_compressor")) {
    function str_compressor($str)
    {
        return preg_replace("/\s+/", ' ', $str);
    }
}

if (!function_exists("minify")) {
    function minify($source)
    {
        $comp = new \PHPXI\Libraries\Minify\Minify($source);
        return $comp->__toString();
    }
}
