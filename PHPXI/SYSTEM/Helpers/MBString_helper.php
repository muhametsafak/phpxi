<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

if(!function_exists("mb_substr")){

    function mb_substr($str, $start, $lenght = null, $encoding = null)
    {
        return substr($str, $start, $lenght);
    }

}

if(!function_exists("mb_strtolower")){

    function mb_strtolower($str, $encoding = null)
    {
        return strtolower($str);
    }

}

if(!function_exists("mb_strlen")){

    function mb_strlen($str, $encoding = null)
    {
        return strlen($str);
    }

}

if(!function_exists("mb_strpos")){

    function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        return strpos($haystack, $needle, $offset);
    }

}
