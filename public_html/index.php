<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
if (phpversion() < "7.2") {
    echo "Your PHP Version : " . phpversion() . "\n";
    echo "Please, Upgrade PHP (7.2) Version";
    exit;
}

define("PUBLIC_PATH", __DIR__);
define("PHPXI_PATH", dirname(__DIR__) . "/PHPXI/");
define("SYSTEM_PATH", PHPXI_PATH . "SYSTEM/");
define("APPLICATION_PATH", PHPXI_PATH . "APPLICATION/");
define("WEIGHT_PATH", PHPXI_PATH . "WEIGHT/");

require_once SYSTEM_PATH . "Executive.php";
