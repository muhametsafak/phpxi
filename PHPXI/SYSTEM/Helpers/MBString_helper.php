<?php
/**
 * MBString_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    MBString_helper.php @ 2021-05-11T18:20:36.303Z
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

if (!function_exists("mb_substr")) {

    function mb_substr($str, $start, $lenght = null, $encoding = null)
    {
        return substr($str, $start, $lenght);
    }

}

if (!function_exists("mb_strtolower")) {

    function mb_strtolower($str, $encoding = null)
    {
        return strtolower($str);
    }

}

if (!function_exists("mb_strlen")) {

    function mb_strlen($str, $encoding = null)
    {
        return strlen($str);
    }

}

if (!function_exists("mb_strpos")) {

    function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        return strpos($haystack, $needle, $offset);
    }

}
