<?php
define("PATH", realpath("."));
define("PHPXI", realpath("../") . '/PHPXI/');
require_once(PHPXI . "SYSTEM/Core.php");
$phpxi = new PHPXI();
$phpxi->autorun();
unset($db);
