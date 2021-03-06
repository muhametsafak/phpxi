<?php
/**
 * RequestLibrary.php
 *
 * This file is part of PHPXI.
 *
 * @package    RequestLibrary.php @ 2021-05-17T09:12:12.398Z
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

class RequestLibrary
{
    public array $params = [];

    function __construct()
    {
        if(isset($_POST)){
            foreach($_POST as $key => $value){
                $this->params[$key] = $value;
            }
        }
        if(isset($_GET)){
            foreach($_GET as $key => $value){
                $this->params[$key] = $value;
            }
        }

        $params = file_get_contents("php://input");
        if(!empty($params)){
            $params = json_decode($params);
            if(is_array($params)){
                foreach($params as $key => $value){
                    $this->params[$key] = $value;
                }
            }
        }
    }

    public function path()
    {
        return get_current_url_path();
    }

    public function ajax(): bool
    {
        $ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
        return ($ajax === 'XMLHttpRequest');
    }

    public function is(string $path): bool
    {
        $is = true;
        $url = $this->path();
        foreach (Base::$route['patterns'] as $key => $value) {
            $path = preg_replace('#' . $key . '#', $value, $path);
        }
        $pattern = '#^' . $path . '$#';
        if (!preg_match($pattern, $url, $params)) {
            $is = false;
        }
        return $is;
    }

    public function method(): string
    {
        if (isset($_SERVER["REQUEST_METHOD"])) {
            return strtolower($_SERVER["REQUEST_METHOD"]);
        }
        return "get";
    }

    public function isMethod($method): bool
    {
        if($this->method() == $method){
            return true;
        }else{
            return false;
        }
    }

    public function param(string $key, $default_value = false)
    {
        if($this->has($key)){
            return $this->params[$keys];
        }else{
            return $default_value;
        }
    }

    public function has(string $key): bool
    {
        if(isset($this->params[$key])){
            return true;
        }else{
            return false;
        }
    }

}
