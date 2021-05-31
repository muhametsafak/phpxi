<?php
/**
 * Language_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    Language_helper.php @ 2021-05-30T05:44:09.123Z
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

use \PHPXI\Libraries\Language\Language as Language;

if(!function_exists("__r")){
    function __r(string $key, string $default = "", array $context = []): string
    {
        return Language::r($key, $default, $context);
    }
}

if(!function_exists("__e")){
    function __e(string $key, string $default = "", array $context = []): void
    {
        echo Language::r($key, $default, $context);
    }
}