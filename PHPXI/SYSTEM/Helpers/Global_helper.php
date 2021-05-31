<?php
/**
 * Global_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    Global_helper.php @ 2021-05-30T06:15:26.544Z
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


if(!function_exists("interpolate")){
    function interpolate(string $message, array $context = []): string
    {
        $replace = array();
        $i = 0;
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
                $replace['{' . $i . '}'] = $val;
                $i++;
            }
        }

        return strtr($message, $replace);
    }
}

if (!function_exists("arrayObject")) {
    function arrayObject($array)
    {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = arrayObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
}

if (!function_exists("str_compressor")) {
    function str_compressor($str)
    {
        return preg_replace("/\s+/", ' ', $str);
    }
}

if (!function_exists("minify")) {
    function minify($source)
    {
        $comp = new \PHPXI\Libraries\Minify\Minify($source);
        return $comp->__toString();
    }
}
