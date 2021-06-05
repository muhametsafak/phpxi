<?php
/**
 * Cache.php
 *
 * This file is part of PHPXI.
 *
 * @package    Cache.php @ 2021-05-11T23:34:44.109Z
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

namespace Config;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class Cache
{

    /**
     * HTML Cache Settings
     *
     * status boolean
     * Set it to true to turn on HTML caching.
     *
     * timeout integer
     * Specifies the validity period of HTML caches in seconds.
     * 1 hour is 3600 seconds.
     * 1 day is 86400 seconds.
     * It has no effect if HTML caching is not enabled.
     *
     * path string
     * Specifies the full directory path to store HTML caching.
     *
     */
    const HTML = [
        "status" => false,
        "timeout" => 86400,
        "path" => WEIGHT_PATH . 'HTML/'
    ];

}
