<?php
if(phpversion() < "7.1"){
    echo "Your PHP Version : ".phpversion()."\n";
    echo "Please, Upgrade PHP (7.1) Version";
    exit;
}

define("PUBLIC_PATH", realpath("."));
define("PHPXI_PATH", realpath("../") . "/PHPXI/");
define("SYSTEM_PATH", PHPXI_PATH . "SYSTEM/");
define("APPLICATION_PATH", PHPXI_PATH . "APPLICATION/");
define("WEIGHT_PATH", PHPXI_PATH . "WEIGHT/");

require_once(SYSTEM_PATH . "Executive.php");

