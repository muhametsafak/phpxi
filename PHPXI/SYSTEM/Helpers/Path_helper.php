<?php
/**
 * Path_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    Path_helper.php @ 2021-05-11T18:22:35.426Z
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

if(!defined("INDEX")){ die("You are not authorized to access"); }

if (!function_exists("public_path")) {
    function public_path($path = "")
    {
        return rtrim(PUBLIC_PATH, "/") . "/" . ltrim(trim($path), "/");
    }
}

if (!function_exists("phpxi_path")) {
    function phpxi_path($path = "")
    {
        return rtrim(PHPXI_PATH, "/") . "/" . ltrim(trim($path), "/");
    }
}

if (!function_exists("system_path")) {
    function system_path($path = "")
    {
        return rtrim(SYSTEM_PATH, "/") . "/" . ltrim(trim($path), "/");
    }
}

if (!function_exists("app_path")) {
    function app_path($path = "")
    {
        return rtrim(APPLICATION_PATH, "/") . "/" . ltrim(trim($path), "/");
    }
}
