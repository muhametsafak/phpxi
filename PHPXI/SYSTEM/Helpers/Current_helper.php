<?php
/**
 * Current_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    Current_helper.php @ 2021-05-11T18:18:36.996Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6
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

use \PHPXI\Libraries\Language\Language as Language;

if (!function_exists("current_url")) {
    function current_url()
    {
        if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == "on") {
            $protocol = "https";
        } else {
            $protocol = "http";
        }
        $url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return $url;
    }
}

if (!function_exists("current_language")) {
    function current_language()
    {
        return Language::get();
    }
}

if (!function_exists("cpu_use")) {
    function cpu_use()
    {
        $xi_server_cpu_usage = false;
        if (function_exists("sys_getloadavg")) {
            $sys_getloadavg = sys_getloadavg();
            $xi_server_cpu_usage = $sys_getloadavg[0];
        } else {
            if (function_exists("exec") and strtolower(PHP_OS_FAMILY) == "windows") {
                exec("wmic cpu get loadpercentage", $output, $value);
                if ($value == 0) {
                    $xi_server_cpu_usage = $output[1];
                }
            }
        }
        return $xi_server_cpu_usage;
    }
}
