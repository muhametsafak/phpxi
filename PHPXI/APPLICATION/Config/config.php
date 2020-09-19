<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}


$config["multi_language"] = true;

/**
 * For language support, specify the language to be installed by default.
 * Example : path to language file is /PHPXI/APPLICATION/Languages/en/app.php
 * $config["language"] = "en";
 */
$config["language"] = "en";

/**
 * Default Controller Configuration and Methods
 * Methods "|" must be separated with.
 * $config["controller"] = array("default_controller" => "home", "default_method" => "index", "method" => "get|post");
 */
$config["controller"] = array(
    "default_controller" => "welcome",
    "default_method" => "index",
    "method" => "get|post"
);

/**
 * If you are going to use sessions, set it to "true".
 * $config['session'] = true;
 */
$config['session'] = true;

/**
 * Site URL - Base URL
 * $config["base_url"] = "http://www.phpxi.net";
 */
$config["base_url"] = "http://localhost/phpxi/public";

/**
 * If you are going to develop, choose "development".
 * You will publish the project. Specify it as "production".
 * define("MODE", "development");
 */
define("MODE", "development");
