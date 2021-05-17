<?php
/**
 * Core.php
 *
 * This file is part of PHPXI.
 *
 * @package    Core.php @ 2021-05-11T18:34:01.515Z
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

namespace PHPXI;

use PHPXI\Libraries\Cache\Cache as Cache;
use PHPXI\Libraries\Routing\Route as Route;

class Core
{

    /**
     * @var mixed
     */
    private static $html_output;

    /**
     * @var mixed
     */
    private static $cache_status = false;

    public static function output()
    {
        if (\Config\Cache::HTML['status']) {
            self::$cache_status = \Config\Cache::HTML['status'];
        }
        if (self::$cache_status) {
            Cache::path(\Config\Cache::HTML['path']);
            Cache::timeout(\Config\Cache::HTML['timeout']);
            Cache::file("%%" . md5(current_url()) . "%%.html");
            if (!Cache::is() || Cache::is_timeout()) {
                self::execute();

                if (self::$cache_status) {
                    Cache::content(self::$html_output);
                    if (!Cache::is()) {
                        Cache::create();
                    } else {
                        Cache::write();
                    }
                }

                return self::$html_output;
            } else {
                return Cache::read();
            }
        } else {
            return self::execute();
        }
    }

    private static function execute()
    {
        $dispatch = Route::dispatch();
        self::$html_output = $dispatch["output"];
        if (\Config\Config::HTML_OUTPUT_MINIFY) {
            self::$html_output = minify(self::$html_output);
        }
        self::$cache_status = $dispatch["cache"];

        return self::$html_output;
    }

}
