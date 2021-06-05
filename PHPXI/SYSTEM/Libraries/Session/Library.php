<?php
/**
 * Session/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Session/Library.php @ 2021-05-11T22:03:38.028Z
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


namespace PHPXI\Libraries\Session;

if(!defined("INDEX")){ die("You are not authorized to access"); }

use PHPXI\Libraries\Base\Base as Base;

class Library
{

    public function unset(): void {
        session_unset();
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function id()
    {
        return session_id();
    }

    /**
     * @param string $key
     */
    public function get(string $key)
    {
        return Base::get($key, "session");
    }

    /**
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value): self
    {
        $_SESSION[$key] = $value;
        Base::set($key, $value, "session");

        return $this;
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
     * @return mixed
     */
    public function delete($key): self
    {
        Base::set($key, null, "session");
        $_SESSION[$key] = null;
        unset($_SESSION[$key]);

        return $this;
    }

}
