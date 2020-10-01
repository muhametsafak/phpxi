<?php

function public_path($path = ""){
    return rtrim(PATH, "/") . "/" . ltrim(trim($path), "/");
}

function phpxi_path($path = ""){
    return rtrim(PHPXI, "/") . "/" . ltrim(trim($path), "/");
}

function system_path($path = ""){
    return phpxi_path("SYSTEM/".$path);
}

function app_path($path = ""){
    return phpxi_path("APPLICATION/".$path);
}
