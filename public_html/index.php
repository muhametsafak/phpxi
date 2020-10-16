<?php
if(phpversion() < "7.0"){
    echo "Your PHP Version : ".phpversion()."\n";
    echo "Please, Upgrade PHP (7.0) Version";
    exit;
}
define("PATH", realpath("."));
define("PHPXI", realpath("../") . '/PHPXI/');
require_once(PHPXI . "SYSTEM/Core.php");
$phpxi = new PHPXI();
$phpxi->autorun();
unset($db);
