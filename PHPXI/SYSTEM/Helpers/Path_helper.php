<?php

function public_path($path = ""){
    return rtrim(PUBLIC_PATH, "/") . "/" . ltrim(trim($path), "/");
}

function phpxi_path($path = ""){
    return rtrim(PHPXI_PATH, "/") . "/" . ltrim(trim($path), "/");
}

function system_path($path = ""){
    return rtrim(SYSTEM_PATH, "/") . "/" . ltrim(trim($path), "/");
}

function app_path($path = ""){
    return rtrim(APPLICATION_PATH, "/") . "/" . ltrim(trim($path), "/");
}
