<?php
/**
 * Http/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Http/Library.php @ 2021-05-11T21:43:40.389Z
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

namespace PHPXI\Libraries\Http;

use PHPXI\Libraries\Base\Base as Base;

class Library
{

    /**
     * @param string $url
     */
    public function scheme(string $url)
    {
        return parse_url($url, PHP_URL_SCHEME);
    }

    /**
     * @param string $url
     */
    public function host(string $url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * @param string $url
     */
    public function port(string $url)
    {
        return parse_url($url, PHP_URL_PORT);
    }

    /**
     * @param string $url
     */
    public function user(string $url)
    {
        return parse_url($url, PHP_URL_USER);
    }

    /**
     * @param string $url
     */
    public function pass(string $url)
    {
        return parse_url($url, PHP_URL_PASS);
    }

    /**
     * @param string $url
     */
    public function path(string $url)
    {
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * @param string $url
     */
    public function query(string $url)
    {
        return parse_url($url, PHP_URL_QUERY);
    }

    /**
     * @param string $url
     */
    public function fragment(string $url)
    {
        return parse_url($url, PHP_URL_FRAGMENT);
    }

    /**
     * @param int $code
     */
    public function response(int $code = 200): void
    {
        http_response_code($code);
    }

    public static function userAgent()
    {
        return Base::get("HTTP_USER_AGENT", "server");
    }

}
