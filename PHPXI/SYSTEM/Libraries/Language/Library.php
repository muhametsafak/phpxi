<?php
/**
 * Language/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Language/Library.php @ 2021-05-11T20:57:47.285Z
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

namespace PHPXI\Libraries\Language;

use PHPXI\Libraries\Base\Base as Base;

class Library
{

    /**
     * @param string $set
     * @return mixed
     */
    public function set(string $set): self
    {
        Base::$data['language']['settings']['set'] = $set;
        $this->load();

        return $this;
    }

    public function get()
    {
        if (isset(Base::$data['language']['settings']['set'])) {
            return Base::$data['language']['settings']['set'];
        }

        return false;
    }

    public function load(): void
    {
        $path = APPLICATION_PATH . "Languages/" . Base::$data['language']['settings']['set'] . "/app.php";
        if (file_exists($path)) {
            $lang = array();
            require_once $path;
            Base::set("lang", $lang, "language");
        } else {
            $this->set(\Config\Config::DEFAULT_LANGUAGE);
        }
    }

    /**
     * @param string $message
     * @param array $context
     */
    private function interpolate(string $message, array $context = []): string
    {
        $replace = array();
        $i = 0;
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
                $replace['{' . $i . '}'] = $val;
                $i++;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * @param string $key
     * @param array $value
     * @return mixed
     */
    public function r(string $key, array $value = []): string
    {
        $lang = Base::get("lang", "language");
        if (isset($lang[$key])) {
            $return = $lang[$key];
        } else {
            $return = $key;
        }
        if (sizeof($value) > 0) {
            $return = $this->interpolate($return, $value);
        }

        return $return;
    }

    /**
     * @param string $key
     * @param array $value
     */
    public function e(string $key, array $value = [])
    {
        echo $this->r($key, $value);
    }

}
