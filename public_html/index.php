<?php
if(phpversion() < "7.4"){
    echo "Your PHP Version : ".phpversion()."\n";
    echo "Please, Upgrade PHP (7.4) Version";
    exit;
}
define("PATH", realpath("."));
define("PHPXI", realpath("../") . '/PHPXI/');
require_once(PHPXI . "SYSTEM/Core.php");
$phpxi = new PHPXI();
require_once(APP . 'Route/Web.php');
$phpxi->run();
unset($db);
