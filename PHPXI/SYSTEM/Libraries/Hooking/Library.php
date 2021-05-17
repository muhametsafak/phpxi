<?php
/**
 * Hook/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Hook/Library.php @ 2021-05-11T21:38:49.268Z
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

namespace PHPXI\Libraries\Hooking;

class Library
{
    /**
     * @var mixed
     */
    private $hook;

    /**
     * @param $where
     * @param $function
     * @param $primacy
     */
    public function add_action($where, $function, $primacy)
    {
        if (!isset($this->hook[$where])) {
            $this->hook[$where] = array();
        }
        $this->hook[$where][$function] = $primacy;
    }

    /**
     * @param $where
     * @param $function
     */
    public function remove_action($where, $function)
    {
        if (isset($this->hook[$where][$function])) {
            unset($this->hook[$where][$function]);
        }

    }

    /**
     * @param $where
     * @param $args
     */
    public function action_work($where, $args)
    {
        if (isset($this->hook[$where])) {
            $dizi = $this->hook[$where];
            arsort($dizi);
            foreach ($dizi as $function => $primacy) {
                call_user_func_array($function, $args);
            }
        }
    }

}
