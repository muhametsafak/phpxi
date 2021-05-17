<?php
/**
 * My_helper.php
 *
 * This file is part of PHPXI.
 *
 * @package    My_helper.php @ 2021-05-12T23:36:11.619Z
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

function starting_msg()
{
    $starting = [
        "You can start developing from the <code title=\"" . APPLICATION_PATH . "\">/PHPXI/APPLICATION/</code> directory.",
        "PHPXI; it is a simple, straightforward and powerful framework.",
        "It can be learned in less than 1 hour."
    ];
    $id = rand(0, 2);

    return $starting[$id];
}