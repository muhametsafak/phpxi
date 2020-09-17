<?php
define("PHPXI", true);

define("PATH", dirname("__FILE__"));

define("SYSTEM", realpath("../") . '/phpxi_system/');
define("APP", realpath("../") . '/phpxi_application/');

require_once(SYSTEM . "phpxi.php");

$phpxi = new PHPXI();

unset($db); unset($mongodb);
