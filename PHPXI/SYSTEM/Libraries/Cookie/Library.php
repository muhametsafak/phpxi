<?php
/**
 * Cookie/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Cookie/Library.php @ 2021-05-11T20:22:02.022Z
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

namespace PHPXI\Libraries\Cookie;

if(!defined("INDEX")){ die("You are not authorized to access"); }

use PHPXI\Libraries\Base\Base as Base;

class Library
{
    /**
     * @var mixed
     */
    private $prefix = null;

    /**
     * @var mixed
     */
    private $timeout = null;
    /**
     * @var array
     */
    private $cookie = [];
    /**
     * @var string
     */
    private $path = '/';
    /**
     * @var string
     */
    private $domain = '';
    /**
     * @var mixed
     */
    private $secure = false;

    /**
     * @param int $time
     * @return mixed
     */
    public function timeout(int $time = 3600): self
    {
        $this->timeout = $time;

        return $this;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function path(string $path = '/'): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $domain
     * @return mixed
     */
    public function domain(string $domain = ''): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param bool $secure
     * @return mixed
     */
    public function secure(bool $secure = false): self
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value): self
    {
        if (is_null($this->prefix)) {
            $this->prefix = \Config\Cookie::PREFIX;
        }
        if (is_null($this->timeout)) {
            $this->timeout = \Config\Cookie::TIMEOUT;
        }
        Base::set($key, $value, "cookie");
        $time = $this->timeout + time();
        $id = $this->prefix . $key;
        setcookie($id, $value, $time, $this->path, $this->domain, $this->secure, true);

        return $this;
    }

    /**
     * @param $key
     */
    public function get($key)
    {
        return Base::get($key, "cookie");
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function add($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function update($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        $this->set($key, null);
        $time = time() - 3600;
        $id = $this->prefix . $key;
        setcookie($id, null, $time);
    }

}
