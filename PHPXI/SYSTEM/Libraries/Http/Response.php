<?php
/**
 * Response.php
 *
 * This file is part of PHPXI.
 *
 * @package    Response.php @ 2021-05-17T09:12:20.106Z
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


namespace PHPXI\Libraries\Http;

if(!defined("INDEX")){ die("You are not authorized to access"); }

use \PHPXI\Libraries\Base\Base as Base;

class Response
{

    private ResponseLibrary $response;

    public function __construct()
    {
        $this->response = new \PHPXI\Libraries\Http\ResponseLibrary();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->response->$name(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        return (new self())->$name(...$arguments);
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        Base::set($property, $value, "response");
    }

    /**
     * @param $property
     */
    public function __get($property)
    {
        return Base::get($property, "response");
    }

}
