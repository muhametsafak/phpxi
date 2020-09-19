<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}

/**
 * Specify database connection settings here.
 */

/*
$config["db"]["example"] = array(
    "host"      => "localhost",
    "user"      => "root",
    "password"  => "",
    "name"      => "phpxi",
    "prefix"    => "",
    "charset"   => "utf8"
);
*/

/**
 * To connect to the database you must specify it in the "/phpxi_application/config/autoload.php" file
 */
$config["db"]["default"] = array(
    "host"      => "localhost",
    "user"      => "root",
    "password"  => "",
    "name"      => "phpxi",
    "prefix"    => "",
    "charset"   => "utf8"
);


$config["db"]["test"] = array(
    "host"      => "localhost",
    "user"      => "root",
    "password"  => "",
    "name"      => "phpxi",
    "prefix"    => "",
    "charset"   => "utf8"
);


/**
 * PHPXI experimentally includes a MongoDB library.
 * Note that this feature is experimental and still under development.
 */

/*
$config["mongodb"]["example"] = array(
    "connect"   => false,
    "host"      => "localhost",
    "user"      => "root",
    "password"  => "",
    "name"      => "phpxi",
    "port"      => "",
    "charset"   => "utf8"
);
*/
$config["mongodb"]["default"] = array(
    "connect"   => false,
    "host"      => "localhost",
    "user"      => "root",
    "password"  => "",
    "name"      => "phpxi",
    "port"      => "27017",
    "charset"   => "utf8"
);
