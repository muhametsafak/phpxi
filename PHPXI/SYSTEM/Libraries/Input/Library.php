<?php
/**
 * Input/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Input/Library.php @ 2021-05-11T21:52:16.728Z
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

namespace PHPXI\Libraries\Input;

use PHPXI\Libraries\Base\Base as Base;
use PHPXI\Libraries\Validation\Validation as Validation;

class Library
{

    /**
     * @param string $key
     * @return mixed
     */
    public function post(string $key = '', array $options = [])
    {
        $post = Base::get("POST", "input");
        if ($key == "") {
            return $post;
        } else {
            if (isset($post[$key])) {
                if (is_array($options) && sizeof($options) > 0) {
                    return $this->validation($post[$key], $options);
                } else {
                    return $post[$key];
                }
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = "", array $options = [])
    {
        $get = Base::get("GET", "input");
        if ($key == "") {
            return $get;
        } else {
            if (isset($get[$key])) {
                if (is_array($options) && sizeof($options) > 0) {
                    return $this->validation($get[$key], $options);
                } else {
                    return $get[$key];
                }
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function files(string $key = "", array $options = [])
    {
        $files = Base::get("FILES", "input");
        if ($key == "") {
            return $files;
        } else {
            if (isset($files[$key])) {
                if (is_array($options) && sizeof($options) > 0) {
                    return $this->validation($files[$key], $options);
                } else {
                    return $files[$key];
                }
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function request(string $key = "", array $options = [])
    {
        $request = Base::get("REQUEST", "input");
        if ($key == "") {
            return $request;
        } else {
            if (isset($request[$key]) and trim($request[$key]) != "") {
                if (is_array($options) && sizeof($options) > 0) {
                    return $this->validation($request[$key], $options);
                } else {
                    return $request[$key];
                }
            } else {
                return false;
            }
        }
    }

    /**
     * @param $data
     * @param array $options
     * @return mixed
     */
    private function validation($data, array $options = [])
    {
        $validation = Validation::load(["input_data" => $data]);
        foreach ($options as $rule) {
            $validation->rule($rule, "input_data");
        }
        if ($validation->validation()) {
            return $data;
        } else {
            return false;
        }
    }

}
