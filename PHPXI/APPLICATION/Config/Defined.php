<?php

define("HTML_Compressor", true);

/**
 * For language support, specify the language to be installed by default.
 * Example : path to language file is /PHPXI/APPLICATION/Languages/en/app.php
 * define("DEFAULT_LANGUAGE", "en");
 */
define("DEFAULT_LANGUAGE", "en");

define("MULTI_LANGUAGES", true);

/**
 * Controller
 */
define("DEFAULT_CONTROLLER_NAME", "Welcome");

define("DEFAULT_CONTROLLER_FUNCTION", "index");

define("DEFAULT_CONTROLLER_METHOD", "get|post");

define("DEFAULT_CONTROLLER_404", "Welcome@not_found");

define("FORCE_CONTROLLER_NAME", "Home");

define("FORCE_CONTROLLER_FUNCTION", "index");

/**
 * BASE URL
 * define("BASE_URL", "http://localhost/phpxi/public_html");
 */
define("BASE_URL", "http://localhost/phpxi/public_html");

/**
 * If you are going to develop, choose "development".
 * You will publish the project. Specify it as "production".
 * define("ENV", "development");
 */
define("ENV", "development");
