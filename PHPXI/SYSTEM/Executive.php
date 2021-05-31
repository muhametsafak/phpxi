<?php
/**
 * Executive.php
 *
 * This file is part of PHPXI.
 *
 * @package    Executive.php @ 2021-05-11T18:31:10.035Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace PHPXI;

define("VERSION", "1.6.2");
$msure = microtime();
$msure = explode(' ', $msure);
$msure = $msure[1] + $msure[0];
define("TIMER_START", $msure);
$phpxi_starting_mermory_use = memory_get_usage();

require_once SYSTEM_PATH . 'Libraries/Autoloader/Autoloader.php';

$autoloader = new \PHPXI\Libraries\Autoloader\PSR4();
$autoloader->register();
$autoloader->addNamespace("PHPXI", SYSTEM_PATH . "/");
$autoloader->addNamespace("Interfaces", SYSTEM_PATH . "/Interfaces/");
$autoloader->addNamespace("Application", APPLICATION_PATH . "/");
$autoloader->addNamespace("Config", APPLICATION_PATH . "/Config/");

$autoload_prefix = \Config\Autoload::PREFIX;
if (sizeof($autoload_prefix) > 0) {
    foreach ($autoload_prefix as $prefix => $path) {
        $autoloader->addNamespace($prefix, $path);
    }
    unset($prefix);unset($path);
}
unset($autoload_prefix);

require SYSTEM_PATH . 'Libraries/Autoloader/ClassMapping.php';

foreach ($class_map as $alias => $origin) {
    if (class_exists($origin)) {
        class_alias($origin, $alias);
    }
}
unset($class_map);

if (\Config\Development::PHP_DEFAULT_ERROR_REPORTING_STATUS) {
    error_reporting(array_sum(array_keys(\Config\Development::PHP_DEFAULT_ERROR_REPORTING_ACTION, $search = true)));
    ini_set("display_errors", 1);
} else {
    error_reporting(0);
    ini_set("display_errors", 0);
}
new \PHPXI\Libraries\Debugging\Debug();

session_set_cookie_params(
    \Config\Session::TIME,
    \Config\Session::PATH,
    \Config\Session::DOMAIN,
    \Config\Session::SECURE,
    \Config\Session::HTTPONLY
);

session_save_path(\Config\Session::REPOSITORY);

session_start();

session_regenerate_id(\Config\Session::REGENERATE_ID);

date_default_timezone_set(\Config\Config::TIMEZONE);

if (function_exists("mb_internal_encoding")) {
    mb_internal_encoding(\Config\Config::CHARSET);
} else {
    require_once SYSTEM_PATH . "Helpers/MBString_helper.php";
}

require_once SYSTEM_PATH . "Helpers/Global_helper.php";

require_once SYSTEM_PATH . "Helpers/Language_helper.php";

require_once SYSTEM_PATH . "Helpers/Url_helper.php";

require_once SYSTEM_PATH . "Helpers/Current_helper.php";

require_once SYSTEM_PATH . "Helpers/Database_helper.php";

require_once SYSTEM_PATH . "Helpers/Path_helper.php";


define("CURRENT_URL", current_url());
define("CURRENT_LANGUAGE", current_language());

new \PHPXI\Libraries\Base\Base();

$core = new \PHPXI\Core();

require_once APPLICATION_PATH . 'Route/Main.php';

$msure = microtime();
$msure = explode(' ', $msure);
$msure = $msure[1] + $msure[0];

$memory_use_kb = (memory_get_usage() - $phpxi_starting_mermory_use) / 1024;
$memory_use_mb = $memory_use_kb / 1024;
$memory_use_gb = $memory_use_mb / 1024;
if($memory_use_gb >= 1){
    define("MEMORY_USE", ceil($memory_use_gb) . 'GB');
}elseif ($memory_use_mb >= 1) {
    define("MEMORY_USE", ceil($memory_use_mb) . 'MB');
} else {
    define("MEMORY_USE", ceil($memory_use_kb) . 'KB');
}
unset($memory_use_kb);
unset($memory_use_mb);
unset($memory_use_gb);

define("MEMORY_USE_MAX", round(memory_get_peak_usage() / 1048576, 3));
define("LOAD_TIME", round(($msure - TIMER_START), 5));
unset($msure);

echo $core->output();

unset($core);