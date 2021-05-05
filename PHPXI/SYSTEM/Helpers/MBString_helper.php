<?php

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

