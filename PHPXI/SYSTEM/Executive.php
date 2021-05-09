<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI;

define("VERSION", "1.5.1");

$memory_use_starting = memory_get_usage();
$msure = microtime();
$msure = explode(' ', $msure);
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);

require_once APPLICATION_PATH . "Config/Define.php";

require_once SYSTEM_PATH . 'Libraries/Autoloader/Autoload_PSR4.php';

$autoload = new \Autoloader\Psr4AutoloaderClass();
$autoload->register();
$autoload->addNamespace("PHPXI", SYSTEM_PATH . "/");
$autoload->addNamespace("Application", APPLICATION_PATH . "/");

require SYSTEM_PATH . 'Libraries/Autoloader/ClassMapping.php';

foreach ($class_map as $alias => $origin) {
    if (class_exists($origin)) {
        class_alias($origin, $alias);
    }
}

if (!defined("DEVELOPMENT")) {
    define("DEVELOPMENT", false);
}

error_reporting(0);
ini_set("display_errors", 0);

if (DEVELOPMENT) {
    new \PHPXI\Libraries\Debugging\Debug();
}

use \PHPXI\Libraries\Base\Base as Base;
Base::main();

use \PHPXI\Libraries\Config\Config as Config;

date_default_timezone_set(Config::get("config.timezone"));

if (function_exists("mb_internal_encoding")) {
    mb_internal_encoding(Config::get("config.charset"));
} else {
    require_once SYSTEM_PATH . "Helpers/MBString_helper.php";
}

session_set_cookie_params(
    Config::get("session.time"),
    Config::get("session.path"),
    Config::get("session.domain"),
    Config::get("session.secure"),
    Config::get("session.httponly")
);

session_save_path(Config::get("session.repository"));

session_start();

session_regenerate_id(Config::get("session.regenerate_id"));

require_once SYSTEM_PATH . "Helpers/Url_helper.php";

require_once SYSTEM_PATH . "Helpers/Database_helper.php";

require_once SYSTEM_PATH . "Helpers/Path_helper.php";

require_once SYSTEM_PATH . "Helpers/Current_helper.php";

require_once SYSTEM_PATH . "Helpers/Object_helper.php";

require_once SYSTEM_PATH . "Helpers/String_helper.php";


if (sizeof(Config::get("autoload.helper")) > 0) {
    foreach (Config::get("autoload.helper") as $row) {
        require APPLICATION_PATH . 'Helpers/' . ucfirst($row) . '_helper.php';
    }
}

require_once SYSTEM_PATH . 'Model.php';
require_once SYSTEM_PATH . 'Controller.php';

if (is_array(Config::get("autoload.model")) and sizeof(Config::get("autoload.model")) > 0) {
    foreach (Config::get("autoload.model") as $alias => $origin) {
        if (class_exists($origin)) {
            class_alias($origin, $alias);
        }
    }
}

require_once SYSTEM_PATH . "Interfaces/FilterInterface.php";

$core = new \PHPXI\Core();

require_once APPLICATION_PATH . 'Route/Web.php';

$msure = microtime();
$msure = explode(' ', $msure);
$msure = $msure[1] + $msure[0];
define("MEMORY_USE", round((memory_get_usage() - $memory_use_starting) / 1048576, 4));
unset($memory_use_starting);
define("MEMORY_USE_MAX", round(memory_get_peak_usage() / 1048576, 3));
define("LOAD_TIME", round(($msure - TIMER_START), 5));

echo $core->output();
