<?php
/**
 * Url_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    Url_helper.php @ 2021-05-11T18:25:02.886Z
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

use \PHPXI\Libraries\Config\Config as Config;

if (!function_exists("base_url")) {
    function base_url(string $path = ""): string
    {
        if (\Config\Config::MULTI_LANGUAGE) {
            $return = rtrim(\Config\Config::BASE_URL, "/") . '/' . CURRENT_LANGUAGE . '/' . ltrim($path, "/");
        } else {
            $return = rtrim(\Config\Config::BASE_URL, "/") . '/' . ltrim($path, "/");
        }

        return $return;
    }
}

if (!function_exists("public_url")) {
    /**
     * @param string $path
     * @param bool $echo
     * @return mixed
     */
    function public_url(string $path = "", bool $echo = true)
    {
        $return = rtrim(\Config\Config::BASE_URL, "/") . '/' . ltrim($path, "/");
        if ($echo) {
            echo $return;
        } else {
            return $return;
        }
    }
}

if (!function_exists("site_url")) {
    /**
     * @param string $path
     * @param bool $echo
     * @return mixed
     */
    function site_url(string $path = "", bool $echo = true)
    {
        $return = base_url($path, false);
        if ($echo) {
            echo $return;
        } else {
            return $return;
        }
    }
}

if (!function_exists("get_referer")) {
    /**
     * @return mixed
     */
    function get_referer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return false;
        }
    }
}

if (!function_exists("redirect")) {
    /**
     * @param string $url
     * @param int $time
     */
    function redirect(string $url = "", int $time = 0): void
    {
        if ($url == "") {
            $url = base_url();
        }
        if ($time == 0) {
            header("Location: " . $url);
        } else {
            header("Refresh:" . $time . "; url=" . $url);
        }
    }
}

if (!function_exists("slug")) {
    /**
     * @param string $value
     * @return mixed
     */
    function slug(string $value): string
    {
        $value = strip_tags(trim($value));
        $find = array(' ', '&amp;quot;', '&amp;amp;', '&amp;', '\r\n', '\n', '/', '\\', '+', '<', '>');
        $value = str_replace($find, '-', $value);
        $find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
        $value = str_replace($find, 'e', $value);
        $find = array('í', 'ý', 'ì', 'î', 'ï', 'I', 'Ý', 'Í', 'Ì', 'Î', 'Ï', 'İ', 'ı');
        $value = str_replace($find, 'i', $value);
        $find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
        $value = str_replace($find, 'o', $value);
        $find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
        $value = str_replace($find, 'a', $value);
        $find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
        $value = str_replace($find, 'u', $value);
        $find = array('ç', 'Ç');
        $value = str_replace($find, 'c', $value);
        $find = array('þ', 'Þ', 'ş', 'Ş');
        $value = str_replace($find, 's', $value);
        $find = array('ð', 'Ð', 'ğ', 'Ğ');
        $value = str_replace($find, 'g', $value);
        $find = array('/[^A-Za-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $value = preg_replace($find, $repl, $value);
        $value = str_replace('--', '-', $value);
        $value = strtolower($value);

        return $value;
    }
}

if (!function_exists("get_current_url_path")) {
    /**
     * @return mixed
     */
    function get_current_url_path(): string
    {
        if (\Config\Config::MULTI_LANGUAGE) {
            $request_uri = mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8");
            $request_uri = mb_strtolower($request_uri, "UTF-8");
            $request_uri = "/" . trim($request_uri, "/");
            $uris = explode("/", ltrim($request_uri, "/"));
            unset($uris[0]);
            $url = "/" . implode("/", $uris);
        } else {
            $dirname = dirname($_SERVER['SCRIPT_NAME']);
            $basename = basename($_SERVER['SCRIPT_NAME']);
            if ($dirname == "/") {
                $request_uri = str_replace($basename, null, $_SERVER["REQUEST_URI"]);
            } else {
                $request_uri = str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
            }
            $url = $request_uri;
        }
        if ($url == "") {
            $url = "/";
        }

        return $url;
    }
}

if(!function_exists("route")){
    function route(string $name, array $parameters = []): string
    {
        return \PHPXI\Libraries\Routing\Route::url($name, $parameters);
    }
}

