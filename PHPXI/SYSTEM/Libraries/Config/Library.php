<?php
/**
 * Config/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Config/Library.php @ 2021-05-11T20:16:24.561Z
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

namespace PHPXI\Libraries\Config;

use PHPXI\Libraries\Base\Base as Base;

class Library
{

    /**
     * @param $key
     * @param $type
     * @return mixed
     */
    public function get($key, $type = false)
    {
        $ids = explode(".", $key);
        if (sizeof($ids) > 1) {
            if (Base::get($ids[0], "config")) {
                $return = Base::get($ids[0], "config");
                unset($ids[0]);
                foreach ($ids as $id) {
                    if (isset($return[$id])) {
                        $return = $return[$id];
                    } else {
                        $return = false;
                    }
                }
            }
        } else {
            if (Base::get($key, "config")) {
                $return = Base::get($key, "config");
            }
        }
        if (isset($return)) {
            if ($type and is_array($return)) {
                $return = arrayObject($return);
            }
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $ids = explode(".", $key);
        if (sizeof($ids) == 1) {
            Base::set($key, $value, "config");

            return true;
        } elseif (sizeof($ids) == 2) {
            $configs = Base::get("", "config");
            $configs[$ids[0]][$ids[1]] = $value;
            Base::set("", $configs, "config");

            return true;
        } else {
            return false;
        }
    }

}
